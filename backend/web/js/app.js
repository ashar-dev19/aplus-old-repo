$(function() {

    

    /* Fee Amount input on application approval */

    if( parseInt( $("#applicationdetail-status").val()) !== 1 || $('#applicationdetail-feeamount').val() != "" ) {

        $('.field-applicationdetail-feeamount').hide();

    }

        $("#applicationdetail-status").change( function(){ console.log(">>" + $(this).val() );



        if( $(this).val()  == 1 ){

            $('.field-applicationdetail-feeamount').show();

        }else{

            $('.field-applicationdetail-feeamount').hide();

        } 



     });

     /* Fee payment confirmation on application enrollment */

    if( parseInt( $("#applicationdetail-status").val()) !== 10) {$('.field-applicationdetail-feepaid').hide();}

    $("#applicationdetail-status").change( function(){ console.log(">>" + $(this).val() );



        if( $(this).val()  == 10 ){

            $('.field-applicationdetail-feepaid').show();

        }else{

            $('.field-applicationdetail-feepaid').hide();

        } 



     });

     

     

     /* Fee Paymenr Amount*/

     if( parseInt( $("#studentfee-status").val()) !== 1 || $('#studentfee-feeamount').val() == 0) {// 

        $('.field-studentfee-paymentamount').hide();

    }

     

     

     $("#studentfee-status").change( function(){ console.log(">>" + $(this).val() );



        if( $(this).val()  == 1 ){

            $('.field-studentfee-paymentamount').show();

        }else{

            $('.field-studentfee-paymentamount').hide();

        } 



     });

     

     /** Student Attendance */

     

     $('.student-checkbox').change(function(){

//            console.log($(this).attr('value'));

//            console.log($('.student-checkbox:checked').length  );

            $('.present-span').html($('.student-checkbox:checked').length );

            $('.absent-span').html($('.student-checkbox:not(:checked)').length );

            $(this).parent().addClass('text-danger bg-danger')

            if( $(this).is(':checked')) $(this).parent().removeClass('text-danger bg-danger')

        })

        

    /** Print */

    var cdate ;

    var currentdate = new Date(); 

    var datetime = "Now: " + currentdate.getDate() + "/"

                + (currentdate.getMonth()+1)  + "/" 

                + currentdate.getFullYear() + " @ "  

                + currentdate.getHours() + ":"  

                + currentdate.getMinutes() + ":" 

                + currentdate.getSeconds();



    cdate = currentdate.getDate() + "-"

                + (currentdate.getMonth()+1)  + "-" 

                + currentdate.getFullYear();

        

    $('#grid-print, .grid-print').on('click', function(){

     

     

//   $('table').attr('border','1');

   var grid = $(this).attr('data');

   

   var gridData = $.parseJSON(grid);

   

   var gridName = gridData.grid_name;

   var mainTitle = gridData.main_title;

   var logoImage = gridData.logo_image;

   var subTitle = gridData.sub_title;

   var subTitleFloat = gridData.sub_title_float;

   var selectedSection, monthSelected ; var tblPadding = gridData.tblPadding;

   if( typeof($('.section_id')) !="undefined" && $('.section_id option:selected').text()!= "Section" && $('.section_id option:selected').text()!= ""){ 

        monthSelected = $('tr:nth-child(1) td:nth-child(10)').html();

        selectedSection = " of Class "+$('.section_id option:selected').text()+  ", Month: "+monthSelected;

      

        }else{selectedSection = "";}

   

   if($('.print_title_option').val()!= "" && $('.print_title_option').val()!= undefined )  subTitle = $('.print_title_option').val();

   var gridColToHide = gridData.hide_col.split("," );

   

   console.log( );

   

  $('.pagination').hide();  

  $('#'+gridName+'-filters').hide();

  $('.non-print').hide();

  $('.print-only').show();

//   $('table tr td:nth-child(9), th:nth-child(9)').hide()// gr



   $.each(gridColToHide, function(index, val){

       console.log("index"+index+ "value"+val)

       $('table tr td:nth-child('+val+'), th:nth-child('+val+')').hide()

   }) 

//   $('table tr td:nth-child(7), th:nth-child(7)').hide()



//   $('table tr td:nth-child(3), th:nth-child(3)').hide()

    if(gridData.hide_last != 0){

    $('table tr td:last-child').hide()

    $('table tr th:last-child').hide()

   }

   if(subTitleFloat == null || subTitleFloat == "")

       subTitleFloat = "float:left";

   

   if(tblPadding == null || tblPadding == "")

       tblPadding = "1px";



  var divToPrint=document.getElementById(gridName);



  var newWin=window.open('','Print-Window');

  

  



  newWin.document.open();

  var stylesheet = '<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">'

//         +ifbootstrap(gridData)

         +'<style type="text/css"> @media all { body{font-family: Source Sans Pro,Helvetica Neue ,Helvetica,Arial,sans-serif;'

         +' } table, th, td{ font-size: 16px; border-collapse:collapse;          border: 2px solid black;  padding:'+tblPadding+';'

          +'text-align:left;        } a {text-decoration:none; color:black}}  </style>';

// var divHead = '<div style="width:11in">'



 var divHead = '<div >'+'<img src="/img/'+logoImage+'.png" alt="LOGO"  width="120pt" align="center" style="padding:0 15px;" >'

                +'<div style ="font-size:25px; font-weight:bold;text-align: center;" >'

   +mainTitle+' </div>';

//  divHead+='<h2>Student Admission List From 01st April 2017 to 15th April 2017<h2>';

  divHead+='<div style="'+subTitleFloat+'; margin:10px"><span style="text-align:left; float:'+subTitleFloat+'; font-size:22px;"><span>'+subTitle +" "+selectedSection

          +',</span> <span style="font-size:14px;">Date:'+cdate+'</span></span>'

            +'</div></div>';

    console.log('<html><head>'+stylesheet+'</head><body>'+divHead+"<div style='float:left'>"+divToPrint.innerHTML+'</div></body></html>');

  newWin.document.write('<html><head>'+stylesheet+'</head><body>'+divHead+"<div style='float:left'>"+divToPrint.innerHTML+'</div></body></html>');



//  newWin.document.print();

  newWin.print(); 



//   setTimeout(function(){newWin.close(); $('.pagination').show(); $('.non-print').show(); $('table tr td:last-child').show()

//    $('table tr th:last-child').show();  $('#student-grid-filters').show();  },8500);

   

    })

   

  $('._approve_assessment').click(function(e){

      e.preventDefault();

      console.log('Lets Approve');

       var appBtn = $(this);  

      var _url = $(this).attr('href');

        $.ajax({



                            type: "GET",



                            data: $( "#x_payment_form" ).serialize(),		



                            url:  _url,



                            cache: false,



                            success: function(response){ //isPaymentDone = 1; $('#x_payment_form').submit()



                                console.log('response: '. response);

                                 if(response  == 1) appBtn.addClass('d-none');

                                appBtn.parent().append('Approved')

                                

                            }



        })

  });

  

});



function ifbootstrap(gridData){

    if(gridData.bootstrap_layout ==1)

        return  '<!-- Bootstrap 3.3.6 -->  <link href="/assets/46fe9aec/css/bootstrap.css?v=1549641543" rel="stylesheet">';

    

}



 $('.module-checkbox').click(function(){

     $('.core-error').addClass('d-none');

	if($(this).attr('data')== 'core') {

//            alert("You can not un check a core module"); 

            $('.core-error').removeClass('d-none');

            return false;}

        countCredits();

        $(this).parent('tr').addClass('bg bg-success')

       

})

if($('.module-checkbox').length > 0) countCredits();

function countCredits(){

    

     var mcount= 0;

	$.each($('.module-checkbox'), function(i,v){



	if($(this).is(':checked') ){

		console.log($(this).val()+ ">>"+ mcount);

				 mcount = mcount+parseInt($(this).attr('data-credits')); 

		}

	})

	$('.total-credits').html(mcount);

}