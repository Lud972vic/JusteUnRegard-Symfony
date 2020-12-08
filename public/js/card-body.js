<!--Mode carousel quand on clique sur une image-->
baguetteBox.run('.cards-gallery', {
    animation: 'slideIn'
});

<!--Affichage la partie card-body quand la souris se trouve sur une image du mur de photo-->
$(".card").mouseover(function () {
    $(this).find('.cardBodyNone').show();
    $(this).find('.imgMurPhoto').height('20.5rem');
});

$(".card").mouseleave(function () {
    $(this).find('.cardBodyNone').hide();
    $(this).find('.imgMurPhoto').height('30rem');
});
