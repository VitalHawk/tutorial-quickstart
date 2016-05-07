var dialog;
var wrapper;

$(function() {
    var wrap = $("#dialog-form");
    var form,
 
      // From http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#e-mail-state-%28type=email%29
      emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
      
      email = wrap.find( "#email" ),
      password = wrap.find( "#password" ),
      allFields = $( [] ).add( email ).add( password ),
      tips = wrap.find( ".validateTips" );
 
    function updateTips( t ) {
      tips
        .text( t )
        .addClass( "ui-state-highlight" );
      setTimeout(function() {
        tips.removeClass( "ui-state-highlight", 1500 );
      }, 500 );
    }
 
    function checkLength( o, n, min, max ) {
      if ( o.val().length > max || o.val().length < min ) {
        o.addClass( "ui-state-error" );
        updateTips( "Length of " + n + " must be between " +
          min + " and " + max + "." );
        return false;
      } else {
        return true;
      }
    }
 
    function checkRegexp( o, regexp, n ) {
      if ( !( regexp.test( o.val() ) ) ) {
        o.addClass( "ui-state-error" );
        updateTips( n );
        return false;
      } else {
        return true;
      }
    }
 
    function addUser() {
      var valid = true;
      allFields.removeClass( "ui-state-error" );
      valid = valid && checkLength( email, "email", 1, 80 );
      valid = valid && checkLength( password, "password", 3, 16 );
      
//      valid = valid && checkRegexp( name, /^[a-z]([0-9a-z_\s])+$/i, "Username may consist of a-z, 0-9, underscores, spaces and must begin with a letter." );
      valid = valid && checkRegexp( email, emailRegex, "eg. ui@jquery.com" );
      valid = valid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
 
      if ( valid ) {
//        $( "#users tbody" ).append( "<tr>" +
//          "<td>" + name.val() + "</td>" +
//          "<td>" + email.val() + "</td>" +
//          "<td>" + password.val() + "</td>" +
//        "</tr>" );
        var submit = wrap.find("input[type=submit]");
//        var email = $("#dialog-form #email");
//        alert($("#dialog-form #email").val());
        wrap.find("#email").attr("value", wrap.find("#email").val());
        wrap.find("#password").attr("value", wrap.find("#password").val());
        submit.click();
//        alert(email);
        dialog.dialog( "close" );
      }
      return valid;
    }
 
    dialog = $( wrap ).dialog({
      autoOpen: false,
      height: 300,
      width: 350,
      modal: true,
      buttons: {
        "Login": addUser,
        Cancel: function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
        allFields.removeClass( "ui-state-error" );
      }
    });
 
    form = dialog.find( "form" ).on( "submit", function( event ) {
      //event.preventDefault();
      addUser();
      //$(wrapper).find("#submit").click();
    });
    wrapper = wrap;
//    alert($("#login-user").children().text().lastIndexOf("Login"));
//    if ($("#login-user").children().text().lastIndexOf("Login") > 0)
//    $( "#login-user").button().on( "click", function() {
//      dialog.dialog( "open" );
//      $(wrapper).parent().css("top", "24%");
//    });
  });


(function()
{
//    var zzz = ['zzz', 'yyy'];
//    alert(zzz[0]);
//    alert(jQuery("span").hasClass("icon-bar"));
//    $(".navbar-inverse a").addClass("preventDefault");
//    var allLiInNavBar = $(".navbar-inverse li");
//    $(".navbar-inverse a").on("click", function(event)
//    {
//        $.each($(".navbar-inverse li"), function(key, value)
//        {
//            if ($(value).hasClass("active"))
//                $(value).removeClass("active");
//        });
//        
//        $(this).parent().addClass("active");
//    });
//    
//    $.each($(".preventDefault"), function()
//    {
//        $(this).click(function(event)
//        {
//            event.preventDefault();
//        }
//        );
//    }
//    );
//    
    
//    return zzz;
}

)();


//<script type="text/javascript">
//function voteUp(postid){
//        var postid = postid;
//        $(this).siblings('.minus-button').removeClass('disliked');    
//        $(this).toggleClass('liked');
//
//        $.ajax({
//            type:"POST",
//            url:"php/votesystem.php",
//            dataType : 'html',
//            data:'act=like&postid='+postid,
//            success: function(data){
//                $('.plus-button').html(data);
//                alert("Liked with id "+postid);
//            }
//        });
//}
//
//</script>
