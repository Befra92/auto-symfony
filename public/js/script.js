// Effet sur h1 de l'index.html
$().ready(function(){
    $("h1").click(function(){
        $("ul").fadeToggle();
        $("ul").fadeToggle("slow");
        $("ul").fadeToggle("2000");
    });
});