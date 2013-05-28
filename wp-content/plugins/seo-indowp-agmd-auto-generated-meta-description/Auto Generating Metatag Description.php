<?php
/*
Plugin Name: Wp SEO Auto Generating Metatag Description
Plugin URI: http://www.indowp.com/plugins/wp-seo-auto-generating-metatag-description/
Description: Plugins that makes your site SEO boosting by making your defaut description change everytime you add new post on homepage only. This plugins usefull for those running news site or you are an up to date blogger.
Version: 1.0
Author: IndoWP
Author URI: http://www.indowp.com

	Copyright 2012 IndoWP  (email : GG@indoWP.com)

    Wp SEO Auto Generating Metatag Description Plugins is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

add_action( 'wp_head', 'gen_meta_desc' );
function gen_meta_desc()
{
    global $post;
    if ( ! is_home() )
        return;
$args = array( 'numberposts' => '3' );
$recent_posts = wp_get_recent_posts( $args );
$meta ='';
foreach( $recent_posts as $recent ){
$meta .= $recent["post_title"].' - ';
}
$meta = substr($meta,0,-1);
    echo "<!-- This site is optimized with WP SEO Auto Generating Metatag Description - http://www.indowp.com/plugins/wp-seo-auto-generating-metatag-description/ -->\n";    
    echo "<meta name='description' content='{$meta}' />\n";
    echo "<!-- / IndoWP WordPress SEO plugin. -->";
}
?>