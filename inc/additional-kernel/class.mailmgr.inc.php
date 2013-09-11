<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

final class mailmgr
{
    private static $mail_extensions = array('mail' => _default,'sendmail' => 'Sendmail','smtp' => 'SMTP');
    private static $options = array();
    private static $mailer_class = null;
    private static $queue = array();

    public static function init()
    {
        self::$options = settings(array('smtp_hostname','smtp_port','smtp_username','smtp_password','smtp_tls_ssl','sendmail_path','mail_extension'));
        self::$options['smtp_hostname'] = string::decode(self::$options['smtp_hostname']);
        self::$options['smtp_username'] = string::decode(self::$options['smtp_username']);
        self::$options['smtp_password'] = decryptData(self::$options['smtp_password']);
        self::$options['sendmail_path'] = string::decode(self::$options['sendmail_path']);
        self::$options['mail_extension'] = string::decode(self::$options['mail_extension']);
        self::$options['mail_from'] = check_email(string::decode(settings('mailfrom'))) ? string::decode(settings('mailfrom')) : 'postmaster@localhost.de';
        self::$mailer_class = new PHPMailer(true);
        self::$mailer_class->SetFrom(self::$options['mail_from']);
        self::$mailer_class->IsHTML(true);
        self::$mailer_class->SetLanguage(_phpmailer_lang, basePath.'/inc/lang/phpmailer/');
        self::$queue = array('index' => 0);
    }

    public static function get_menu($mail_ext='')
    {
        $options = '';
        foreach(self::$mail_extensions as $tag => $name)
        { $options .= '<option value="'.$tag.'" '.($tag == $mail_ext ? 'selected="selected"' : '').'> '.$name.'</option>'; }
        return '<select id="mail_extension" name="mail_extension" class="dropdown">'.$options.'</select>';
    }

    public static function AddContent($subject='',$content='') //1st
    { if(empty($subject) || empty($content)) return false; self::$queue['index']++; self::$queue[self::$queue['index']] = array('content' => $content, 'subject' => $subject); return true; }

    public static function AddAddress($email='',$name='') //2st
    { if(!check_email($email)) return false; self::$queue[self::$queue['index']]['addresses'][] = array('email' => $email, 'name' => $name); return true; }

    public static function AddAttachment($file='') //3st
    { if(!file_exists(basePath.'/'.$file)) return false; self::$queue[self::$queue['index']]['files'][] = basePath.'/'.$file; return true; }

    public static function Send()
    {
        if(count(self::$queue) >= 2)
        {
            try
            {
                switch (self::$options['mail_extension'])
                {
                    case 'smtp':
                        self::$mailer_class->IsSMTP();
                        self::$mailer_class->Host = self::$options['smtp_hostname'];
                        self::$mailer_class->Port = self::$options['smtp_port'];
                        self::$mailer_class->SMTPAuth = empty(self::$options['smtp_username']) && empty(self::$options['smtp_password']) ? false : true;
                        self::$mailer_class->Username = self::$options['smtp_username'];
                        self::$mailer_class->Password = self::$options['smtp_password'];
                        switch(self::$options['smtp_tls_ssl'])
                        {
                            case 1: self::$mailer_class->SMTPSecure = 'tls'; break;
                            case 2: self::$mailer_class->SMTPSecure = 'ssl'; break;
                            default: self::$mailer_class->SMTPSecure = ''; break;
                        }
                    break;
                    case 'sendmail':
                        self::$mailer_class->IsSendmail();
                        self::$mailer_class->Sendmail = self::$options['sendmail_path'];
                    break;
                    default: self::$mailer_class->IsMail(); break;
                }

                foreach(self::$queue as $id => $tick)
                {
                    if($id == 'index') continue;
                    DebugConsole::insert_info('mailmgr::Send()', 'Send Queue-ID: '.$id);
                    DebugConsole::insert_info('mailmgr::Send()', 'Send Subject: '.$tick['subject']);

                    self::$mailer_class->Subject = $tick['subject'];
                    self::$mailer_class->MsgHTML($tick['content'], dirname(__FILE__));
                    self::$mailer_class->Body = bbcode::nletter($tick['content']);

                    foreach($tick['addresses'] as $address) {
                        self::$mailer_class->AddAddress(preg_replace('/(\\n+|\\r+|%0A|%0D)/i', '',$address['email']), $address['name']);
                        DebugConsole::insert_info('mailmgr::Send()', 'Send to: '.$address['email']); }

                    if(key_exists('files', $tick) && count($tick['files']) >= 1)
                    {
                        foreach($tick['files'] as $file)
                        { self::$mailer_class->AddAttachment($file); DebugConsole::insert_info('mailmgr::Send()', 'Send File: '.$file); }
                    }

                    self::$mailer_class->Send(); //send
                }
            }
            catch (phpmailerException $e)
            { DebugConsole::insert_error('mailmgr::Send()', $e->errorMessage()); }
            catch (Exception $e)
            { DebugConsole::insert_warning('mailmgr::Send()', $e->getMessage()); }
        }
    }
}