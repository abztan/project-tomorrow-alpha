
<!DOCTYPE html><html class=''>
<head><meta charset='UTF-8'><meta name="robots" content="noindex"><link rel="canonical" href="http://codepen.io/senff/pen/ayGvD" />


<style class="cp-pen-styles">* {font-family:arial; margin:0; padding:0;}
.logo {font-size:40px; font-weight:bold;color:#00a; font-style:italic;}
.intro {color:#777; font-style:italic; margin:10px 0;}
.menu {background:#00a; color:#fff; height:40px; line-height:40px;letter-spacing:1px; width:100%;}
.content {margin-top:10px;}
.menu-padding {padding-top:40px;}
.content {padding:10px;}
.content p {margin-bottom:20px;}

</style></head><body>
<header>
  <div class="logo">STICKY MENU ON SCROLL!</div>
  <div class="menu">Menu goes here - home - links - blah blah</div>
</header>

<div class="content">
 
  <em><p>This thingie is also available as a ready-made jQuery plugin at <a href="https://github.com/senff/Sticky-Anything" target="_blank">https://github.com/senff/Sticky-Anything</a> and will allow you to make ANY element sticky -- not just the menu.<br><strong>Now also available as a <a href="https://wordpress.org/plugins/sticky-menu-or-anything-on-scroll/">WordPress plugin!</a></strong></em></p>
  
  <h2>So how does this thing work?</h2>
  
  <p>Upon page load, jQuery clones the menu <em>(= the element we want to see sticking at the top of the screen)</em>. The original menu gets class "original" and the cloned one gets class "cloned".</p>
  
  <p>In the DOM, the cloned one is placed right after the original menu so it becomes a full sibling (twin?) of the original one. With CSS, we make sure that the cloned one is positioned properly to make it stick at the top of the screen -- but is set to <strong>display:none;</strong> so we won't see this sticky one right away.</p>
    
  <p>Every 10ms (why? see <a href="https://medium.com/@dhg/82ced812e61c#ee02" target="_blank">these do's and don'ts</a>), <strong>stickThis()</strong> is fired and checks the scrolled position and compares it to the top coordinate of the menu. If the scrolled position is equal or higher than the menu top coordinate, it means that we've scrolled past the menu and this is when we want it to be sticky. When that is the case, we make the cloned (sticky) show, and make the original (static) one invisible.</p>
  
  <p>Obviously, when the scrolled position is not as high as the original menu's top coordinate, it means we haven't scrolled past the menu, and in this case we'll need the "normal" situation (the cloned one doesn't show and the original one does).</p>
    
    
  <h2>What's up with this cloning thing?</h2>  
  <p>A lot of sticky menu methods just make the original item stick at the top of the screen. That works, but the downside of this is that the menu is removed from the flow of the page. The rest of the page doesn't "see" the menu anymore so this needs to be compensated by adding padding, margin, or something to make sure that the content doesn't "jump" (see "<a href="http://codepen.io/senff/full/zfqEl" target="_blank">HERE</a> how that would happen).</p>
  
  <p>The trick to that is to not remove the menu from the flow at all, so that the rest of the page sees it at all times - sticky or not! Always leave the menu where it is. <br> Of course, making it sticky (by assigning <strong>position:fixed</strong>) will, by definition, remove it from the flow, so we can't make this instance of the menu sticky.</p>
    
  <p>By creating a clone, we can create <em>another</em> (identical) instance of the menu and make that one always sticky. It will always be there, but depending on the scrolled position, it will (or will not) show. When it shouldn't be visible, we can't have it "overlap" or obscure any other elements, which is why we use show/hide instead of visible/invisible (if it would just be invisible, it would technically block everything under it from user interaction).</p>
    
  <p>SOOOOO, the <strong>original</strong> menu toggles between visible/invisible (to not mess with the positioning of the elements on the rest of the page), while the <strong>cloned</strong> menu toggles between show/hide (to not mess with the elements under it).</p>
  
  <h2>TL;DR</h2>  
    
<p>- Page creates a cloned copy of the menu, which will have the exact same look/feel as the original menu (because it's an identical sibling), but is sticky at the top of the page.<br>- When we DON'T need a sticky menu, the original static menu shows normally and the cloned sticky menu is set to not show.<br>- When we DO need a sticky menu, the original static menu is invisible and the cloned sticky one is set to show.</p>
    
  <h2>Dummy content below this line.</h2>  
      
  
<p>Bacon ipsum dolor sit amet culpa adipisicing andouille ut, salami bresaola spare ribs shank fatback cupidatat est. Occaecat beef flank fatback beef ribs. Sed tempor officia, proident ullamco elit short loin ham hock short ribs laborum pariatur. Nisi frankfurter sint, boudin aute andouille chicken corned beef. Shank pariatur pork loin deserunt et nostrud, sausage ut.</p>

<p>Qui sed elit leberkas enim prosciutto aliqua shank occaecat. Labore enim proident short loin strip steak ut. Bresaola ea sed pariatur culpa sint ham hock tri-tip shoulder. Sed jowl sunt chuck mollit jerky.</p>

<p>Ball tip ham hock pariatur dolore, minim pig qui non filet mignon. Duis dolore do pork belly aute. In consequat mollit consectetur dolore. Short ribs duis tempor, deserunt dolore pastrami pancetta do aliquip jerky sed qui spare ribs tri-tip. Qui jerky culpa eu drumstick chicken sausage brisket.</p>
  
<p>Bacon ipsum dolor sit amet culpa adipisicing andouille ut, salami bresaola spare ribs shank fatback cupidatat est. Occaecat beef flank fatback beef ribs. Sed tempor officia, proident ullamco elit short loin ham hock short ribs laborum pariatur. Nisi frankfurter sint, boudin aute andouille chicken corned beef. Shank pariatur pork loin deserunt et nostrud, sausage ut.</p>

<p>Qui sed elit leberkas enim prosciutto aliqua shank occaecat. Labore enim proident short loin strip steak ut. Bresaola ea sed pariatur culpa sint ham hock tri-tip shoulder. Sed jowl sunt chuck mollit jerky.</p>

<p>Ball tip ham hock pariatur dolore, minim pig qui non filet mignon. Duis dolore do pork belly aute. In consequat mollit consectetur dolore. Short ribs duis tempor, deserunt dolore pastrami pancetta do aliquip jerky sed qui spare ribs tri-tip. Qui jerky culpa eu drumstick chicken sausage brisket.</p>

  <p>Bacon ipsum dolor sit amet culpa adipisicing andouille ut, salami bresaola spare ribs shank fatback cupidatat est. Occaecat beef flank fatback beef ribs. Sed tempor officia, proident ullamco elit short loin ham hock short ribs laborum pariatur. Nisi frankfurter sint, boudin aute andouille chicken corned beef. Shank pariatur pork loin deserunt et nostrud, sausage ut.</p>

<p>Qui sed elit leberkas enim prosciutto aliqua shank occaecat. Labore enim proident short loin strip steak ut. Bresaola ea sed pariatur culpa sint ham hock tri-tip shoulder. Sed jowl sunt chuck mollit jerky.</p>

<p>Ball tip ham hock pariatur dolore, minim pig qui non filet mignon. Duis dolore do pork belly aute. In consequat mollit consectetur dolore. Short ribs duis tempor, deserunt dolore pastrami pancetta do aliquip jerky sed qui spare ribs tri-tip. Qui jerky culpa eu drumstick chicken sausage brisket.</p>
  
</div>

<script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'></script>
<script>
// Create a clone of the menu, right next to original.
$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();

scrollIntervalID = setInterval(stickIt, 10);


function stickIt() {

  var orgElementPos = $('.original').offset();
  orgElementTop = orgElementPos.top;               

  if ($(window).scrollTop() >= (orgElementTop)) {
    // scrolled past the original position; now only show the cloned, sticky element.

    // Cloned element should always have same left position and width as original element.     
    orgElement = $('.original');
    coordsOrgElement = orgElement.offset();
    leftOrgElement = coordsOrgElement.left;  
    widthOrgElement = orgElement.css('width');
    $('.cloned').css('left',leftOrgElement+'px').css('top',0).css('width',widthOrgElement).show();
    $('.original').css('visibility','hidden');
  } else {
    // not scrolled past the menu; only show the original menu.
    $('.cloned').hide();
    $('.original').css('visibility','visible');
  }
}
//@ sourceURL=pen.js
</script>

</body></html>