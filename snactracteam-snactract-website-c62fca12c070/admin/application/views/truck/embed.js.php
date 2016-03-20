$(document).ready(function () {
  console.log("entering document.ready in embed.js.php");
  
  $('#copy-embed-code-to-clipboard').click(function(evt){
    evt.preventDefault();
    console.log("click called on copy-embed-code-to-clipboard");
    window.prompt("Copy to clipboard: Ctrl+C, Enter", '<?php echo $text?>');
  });

});
