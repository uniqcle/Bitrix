$(document).ready(function(){
    
    $('.form_ajax').submit(function( event ){

      event.preventDefault();

      var form = $(this);
 
      //var url=form.attr('action');

      //�������� id-������  
      //�������� ���-��

      $.ajax({
          url : '/ajax/test_ajax.php',
          type : 'POST',
          data: form.serialize(),
           // dataType:'json',
          success : function(data) {
            form.find("button").html("<b style = 'color: black;'> �������� </b >"); 
          },
          error : function(request,error)
          {
          // alert("Request: "+JSON.stringify(request));
          }
        });

//#K%*xPf1Rj

    }); 

 

}); 