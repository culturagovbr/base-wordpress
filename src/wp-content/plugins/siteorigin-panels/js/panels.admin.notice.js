/**
 * Hides the admin notices when required.
 *
 * @copyright Greg Priday 2014
 * @license GPL 2.0 http://www.gnu.org/licenses/gpl-2.0.html
 */

jQuery(function($){
    $('.siteorigin-panels-dismiss').click(function(e){
        e.preventDefault();
        var $$ = $(this);
        $.get( $$.attr('href') );
        $$.closest('.updated, .error').slideUp(function(){ $(this).remove(); });
    });
});