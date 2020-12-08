$(function() {
  /***Télécharger une photo de profil ***/
  $(document).ready(function() {
    $(document).on("change", ".btn-file :file", function() {
      var input = $(this),
        label = input
          .val()
          .replace(/\\/g, "/")
          .replace(/.*\//, "");
      input.trigger("fileselect", [label]);
    });

    $(".btn-file :file").on("fileselect", function(event, label) {
      var input = $(this)
          .parents(".input-group")
          .find(":text"),
        log = label;

      if (input.length) {
        input.val(log);
      } else {
        if (log) alert(log);
      }
    });

    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $("#img-upload").attr("src", e.target.result);
          $("#video-upload").attr("src", e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    
    $("#imgProfil").mouseleave(function() {
      readURL(this);
    });
  });

  /***Voir le mot de passe en clair***/
  $("#seemdp").mouseover(function() {
    $("#mdp").attr("type", "text");
  });

  $("#seemdp").mouseleave(function() {
    $("#mdp").attr("type", "password");
  });
});
