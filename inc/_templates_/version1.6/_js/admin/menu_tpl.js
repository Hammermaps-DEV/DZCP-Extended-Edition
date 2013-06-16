// level scope settins structure
var POS = [
// root level configuration (level 0)
{
    // item sizes
    'height': 18,
    'width': 110,
    // absolute position of the menu on the page (in pixels)
    // with centered content use Tigra Menu PRO or Tigra Menu GOLD
    'block_top':  0,
    'block_left': 0,
    // offsets between items of the same level (in pixels)
    'top':  0,
    'left': 111,
    // time delay before menu is hidden after cursor left the menu (in milliseconds)
    'hide_delay': 500,
    // submenu expand delay after the rollover of the parent
    'expd_delay': 0,
    // names of the CSS classes for the menu elements in different states
    // tag: [normal, hover, mousedown]
    'css' : {
        'outer' : ['adminBarOuter', 'adminBarOuterOver'],
        'inner' : ['adminBarInner', 'adminBarInnerOver']
    }
},
// sub-menus configuration (level 1)
// any omitted parameters are inherited from parent level
{
    'height': 21,
    'width': 221,
    // position of the submenu relative to top left corner of the parent item
    'block_top': 19,
    'block_left': 0,
    'top': 21,
    'left': 0,
    'css' : {
        'outer' : ['adminMenuOuter', 'adminMenuOuterOver'],
        'inner' : ['adminMenuInner', 'adminMenuInnerOver']
    }
},
// sub-sub-menus configuration (level 2)
{
    'block_top': 0,
    'block_left': 171
}
// the depth of the menu is not limited
// make sure there is no comma after the last element
];
