var stripPicWidth = 116;
var mobile = false;

// jQuery UI Touch Punch 0.2.3  Copyright 2011–2014, Dave Furfero
!function(a){function f(a,b){if(!(a.originalEvent.touches.length>1)){a.preventDefault();var c=a.originalEvent.changedTouches[0],d=document.createEvent("MouseEvents");d.initMouseEvent(b,!0,!0,window,1,c.screenX,c.screenY,c.clientX,c.clientY,!1,!1,!1,!1,0,null),a.target.dispatchEvent(d)}}if(a.support.touch="ontouchend"in document,a.support.touch){var e,b=a.ui.mouse.prototype,c=b._mouseInit,d=b._mouseDestroy;b._touchStart=function(a){var b=this;!e&&b._mouseCapture(a.originalEvent.changedTouches[0])&&(e=!0,b._touchMoved=!1,f(a,"mouseover"),f(a,"mousemove"),f(a,"mousedown"))},b._touchMove=function(a){e&&(this._touchMoved=!0,f(a,"mousemove"))},b._touchEnd=function(a){e&&(f(a,"mouseup"),f(a,"mouseout"),this._touchMoved||f(a,"click"),e=!1)},b._mouseInit=function(){var b=this;b.element.bind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),c.call(b)},b._mouseDestroy=function(){var b=this;b.element.unbind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),d.call(b)}}}(jQuery);
// jQuery Unveil  http://luis-almeida.github.com/unveil  Copyright 2013 Luís Almeida
!function(t){t.fn.unveil=function(i,e){function n(){var i=a.filter(function(){var i=t(this);if(!i.is(":hidden")){var e=o.scrollTop(),n=e+o.height(),r=i.offset().top,s=r+i.height();return s>=e-u&&n+u>=r}});r=i.trigger("unveil"),a=a.not(r)}var r,o=t(window),u=i||0,s=window.devicePixelRatio>1,l=s?"data-src-retina":"data-src",a=this;return this.one("unveil",function(){var t=this.getAttribute(l);t=t||this.getAttribute("data-src"),t&&(this.setAttribute("src",t),"function"==typeof e&&e.call(this))}),o.on("scroll.unveil resize.unveil lookup.unveil",n),n(),this}}(window.jQuery||window.Zepto);



$(window).click(function() {
    if ($(".closeonclick:visible").length) {
    $( ".closeonclick" ).hide();
  }
});


$('.jqtooltip').tooltip({
  content: function(callback) {
     callback($(this).prop('title').replace('|', '<br />'));
  }
});





  $(function() {

//    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { mobile = true;  }
//    if (!mobile) {$('#dropbox').show(); }

        if (lang === '') { lang = 'en';}
  $.datepicker.setDefaults( $.datepicker.regional[ lang ] );
    $( "#reminder_time" ).datepicker({
      changeMonth: true,
      changeYear: true,
      altField: "#timestampdate",
      altFormat: "@",
      minDate: 1
    });

    $( "#sortimages").sortable({
       handle: ".portlet-header",
       placeholder: "mb1 mr1 p1 black bg-kclite left fauxfield square ",
       cancel: ".meta",
       update: function( event, ui ) {
        var data = $(this).sortable('toArray');
            $('#user_images').val(data);
            if ($(this).hasClass( "autosortsave" ))
            {



              var car_id = $("#car_id").val();
              var chapter = $("#chapter").val();
              var pics = $('#user_images').val();
      $('.response[data-key="attritem-1"]').load('/car/write_attr', {"key":'attritem-1', "value":pics, "car_id":car_id, "chapter":chapter, "entry":'PICTURES', "unit":'', "validate":'text' });










            }
       },
    });

  //  $( "#event_type_select" ).selectmenu();



    if ($( "#accordion" ).length) {$( "#accordion" ).accordion({      heightStyle: "content",  collapsible: true,  active: false    }); }



  });





//message count updater

setInterval(function() {
$.getJSON("/message/msgcount", function(json){
  if (json.MessageCount > 0) {
    $( "#msgcount div" ).html(json.MessageCount);
    $( "#msgcount div" ).removeClass('hide');
    }
});
}, 240000); //4min

//event type dialog

$('#event_type_select').on('change', function () {
    //var optionSelected = $("option:selected", this);
    var evtype_id = this.value;
    var evtype_name = $("#event_type_select option:selected").text();
  addEventTypeToList(evtype_id, evtype_name);
} );

function addEventTypeToList(evtype_id, evtype_name) {
  if ($('#event_type').val().indexOf(evtype_id) == -1) {

$('#event_type').val(function(i, val) {
return (val ? val + ',' : '') + evtype_id;      });
$('#taglisting').append('<li><a href="' + evtype_id + '"><i class="icon-cancel white brdrw"> </i></a> <span>'+evtype_name+'</span></li>');
$('#event_type_select').val('');
$('#reminder_content_proxy').val(evtype_name + ' ' + $('#reminder_content_proxy').val()); // feed the text into reminder textarea

    if (evtype_id === 'TAG_MAINTENANCE') {
      $( "#maintenance_panel" ).removeClass('hide');
    }

  }
}

$(document).on("click","#taglisting > li > a",function(e){
            e.preventDefault();
    var tagtoremove = $(this).attr('href');
    var current_tags = $('#event_type').val();
    var tag_array = current_tags.split(',');
    var index = tag_array.indexOf(tagtoremove);
    if (index > -1) {
    tag_array.splice(index, 1);
    $('#event_type').val(tag_array.join());
    $(this).parent().hide('slow');
    }
            });


//new event dialog
$( "#new_event_dialog" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.85), modal: true });
$( "#new_event_dialog_opener, .new_event_dialog_opener" ).click(function() {  $( "#new_event_dialog" ).dialog( "open" ); });
$( "#new_event_dialog .close_dialog" ).click(function() {  $( "#new_event_dialog" ).dialog( "close" ); });
$( ".new_event_todo_opener" ).click(function(e) {
    e.preventDefault();
    $("#todolist_checkbox").prop( "checked", true );
    $( "#new_event_dialog" ).dialog( "open" );
    });

//odometer update dialog
$( "#odo_dlg" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.45), modal: true });
$( "#odo_dlg_opnr" ).click(function() {  $( "#odo_dlg" ).dialog( "open" ); });
$( "#odo_dlg .close_dialog" ).click(function() {  $( "#odo_dlg" ).dialog( "close" ); });

//reminder delete dialog
$( "#deleteconfdlg" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.45), modal: true });
$( ".deleteconfdlgopnr" ).click(function(e) {
    e.preventDefault();
    $( "#deleteconfdlg" ).dialog( "open" );
    $("#dlg_car_id").val($(this).data('car_id'));
    $("#dlg_time").val($(this).data('time'));
    $("#dlg_microtime").val($(this).data('microtime'));

    });
$( "#deleteconfdlg .close_dialog" ).click(function() {  $( "#deleteconfdlg" ).dialog( "close" ); });

 //car make picker
  $( "#nwcardialog" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.75), modal: true });
$( "#nwcaropener" ).click(function(e) {
    e.preventDefault();
  $( "#nwcardialog" ).dialog( "open" );
});

$( "#nwcardialog .nwmake" ).click(function(e) {
    e.preventDefault();
$("#nwcaropener").val($(this).attr('href'));
$("#car_make_id").val($(this).data('make-id'));
$( "#nwcardialog" ).dialog( "close" );
} );

$( "#nwcardialog #show-all-makes" ).click(function() {
  $( "#nwcardialog .nwmake" ).parent().removeClass('hide');
  $(this).hide();
});


//car model picker
  $( "#nwmodeldialog" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.75), modal: true });
$( "#nwmodelopener" ).click(function(e) {
    e.preventDefault();
  $( "#nwmodeldialog" ).dialog( "open" );
  if (($("#car_make_id").val()) && ($("#car_year").val())) {
  $( "#nwmodeldialog" ).html('<div class="icon-spin3 spinner"> </div>');
  $( "#nwmodeldialog" ).load( "/car/model_list/"+$("#car_make_id").val()+"/"+$("#car_year").val() );
                                }
});

$('body').on('click', '#nwmodeldialog .nwmodel', function(e) {
    e.preventDefault();
$("#nwmodelopener").val($(this).attr('href'));
$("#car_model_id").val($(this).data('model-id'));
  $( "#nwmodeldialog" ).dialog( "close" );
} );


//car variant picker
  $( "#nwvariantdialog" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.75), modal: true });
$( "#nwvariantopener" ).click(function(e) {
    e.preventDefault();
  $( "#nwvariantdialog" ).dialog( "open" );
  if ($("#car_model_id").val()) {
  $( "#nwvariantdialog" ).html('<div class="icon-spin3 spinner"> </div>');
  $( "#nwvariantdialog" ).load( "/car/variant_list/"+$("#car_model_id").val() );
                                }
});

$('body').on('click', '#nwvariantdialog .nwvariant', function(e) {
    e.preventDefault();
$("#nwvariantopener").val($(this).attr('href'));
$("#car_variant_id").val($(this).data('variant-id'));
var maxyear = $(this).data('year-max');
    if ( !maxyear )
{maxyear = new Date().getFullYear();}
$("#car_year").attr({"min" : $(this).data('year-min'), "max" : $(this).data('year-max')});




  $( "#nwvariantdialog" ).dialog( "close" );
} );



//car data bit saver

function saveDataBits() {
    var new_car_data_val = $("#new_car_data_val").val();


            if (new_car_data_val) {
              var new_car_data_bit = $("#new_car_data_bit").val();
              var car_id = $("#car_id").val();

          $( "#car_data_container" ).html('<div class="icon-spin3 spinner"> </div>');
             $("#new_car_data_bit").val('BIT_OTHER');
             $("#new_car_data_val").val('');
            $('#car_data_container').load('/car/car_data_bits', {"new_car_data_bit":new_car_data_bit, "new_car_data_val":new_car_data_val, "car_id":car_id });
                            }
}


            $(document).on("click","#add_new_car_data",function(e){
            e.preventDefault();
           saveDataBits();
            });



$("#new_car_data_val").on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) {
    e.preventDefault();
    saveDataBits();
  }
});


//car data bit delete
$( "#cardatadeletedlg" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.45), modal: true });
$('body').on('click', '.cardatadeletesw', function() {  $( "#cardatadeletedlg" ).dialog( "open" ); the_data_bit = $(this).data('id');    });
$( "#cardatadeletedlg .close_dialog" ).click(function() {  $( "#cardatadeletedlg" ).dialog( "close" ); });
$( "#cardatadeletebtn" ).click(function() {
      var car_id = $("#car_id").val();
      $('#car_data_container').load('/car/remove_car_data_bits', {"bit_id":the_data_bit, "car_id":car_id });
      $( "#cardatadeletedlg" ).dialog( "close" );
  });

//car data bit edit

function editDataBits() {
  var car_id = $("#car_id").val();
      var text_to_save = $("#edit_car_data_val").val();
      $('#car_data_container').load('/car/edit_car_data_bits', {"bit_id":the_data_bit, "key":the_data_bit_key, "value":text_to_save, "car_id":car_id });
      $( "#cardataeditdlg" ).dialog( "close" );
}


$( "#cardataeditdlg" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.45), modal: true });
$('body').on('click', '.cardataeditsw', function() {
  $( "#cardataeditdlg" ).dialog( "open" );
  the_data_bit = $(this).data('id');
  the_data_bit_key = $('.bit_val[data-id="'+the_data_bit+'"]').data('key');
  $("#edit_car_data_val").val($('.bit_val[data-id="'+the_data_bit+'"]').html());
               });
$( "#cardataeditdlg .close_dialog" ).click(function() {  $( "#cardataeditdlg" ).dialog( "close" ); });
$( "#cardatasavebtn" ).click(function() {  editDataBits();        });
$("#edit_car_data_val").on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) {
    e.preventDefault();
    editDataBits();
  }
});



 // user profile editor
$( "#userlangdialog" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.45), modal: true });
$( "#user_lang .icon-cog" ).click(function() {  $( "#userlangdialog" ).dialog( "open" ); });

$( "#usercurrencydialog" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.45), modal: true });
$( "#user_currency .icon-cog" ).click(function() {  $( "#usercurrencydialog" ).dialog( "open" ); });

$( "#userdistancedialog" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.45), modal: true });
$( "#user_distance .icon-cog" ).click(function() {  $( "#userdistancedialog" ).dialog( "open" ); });

$( "#userconsdialog" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.45), modal: true });
$( "#user_cons .icon-cog" ).click(function() {  $( "#userconsdialog" ).dialog( "open" ); });

$( "#hometowndialog" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.45), modal: true });
$( "#hometown .icon-cog" ).click(function() {  $( "#hometowndialog" ).dialog( "open" );
$( "#hometowndialog" ).html('<div class="icon-spin3 spinner"> </div>');
$( "#hometowndialog" ).load( "/login/city_list/"+$("#country_id").val()+"/"+$("#state_id").val()+"/"+$("#city").val() ); });

$( "#statedialog" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.45), modal: true });
$( "#state .icon-cog" ).click(function() {  $( "#statedialog" ).dialog( "open" );
$( "#statedialog" ).html('<div class="icon-spin3 spinner"> </div>');
$( "#statedialog" ).load( "/login/state_list/"+$("#country_id").val()+"/"+$("#state_id").val() ); });

$( "#countrydialog" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.45), modal: true });
$( "#country .icon-cog" ).click(function() {
$( "#countrydialog" ).dialog( "open" );
$( "#countrydialog" ).html('<div class="icon-spin3 spinner"> </div>');
$( "#countrydialog" ).load( "/login/country_list/"+$("#country_id").val() ); });

$( "#sms_checkbox").click(function() {
    cbchecked = '0';
    if(this.checked) { cbchecked = '1'; }
    var spinner = $('<i class="icon-spin3 spinner"> </i>');
    $( "#sms_checkbox_cont" ).append(spinner);
    $( "#responsebox" ).load( "/login/cbox/send_sms/"+cbchecked );
    spinner.remove();
    });

$( "#email_checkbox").click(function() {
    cbchecked = '0';
    if(this.checked) { cbchecked = '1'; }
    var spinner = $('<i class="icon-spin3 spinner"> </i>');
    $( "#email_checkbox_cont" ).append(spinner);
    $( "#responsebox" ).load( "/login/cbox/send_email/"+cbchecked );
    spinner.remove();
    });

  $( "#pubpage_checkbox").click(function() {
        cbchecked = '0';
        if(this.checked) { cbchecked = '1'; }
        var spinner = $('<i class="icon-spin3 spinner"> </i>');
        $( "#pubpage_checkbox_cont" ).append(spinner);
        $( "#responsebox" ).load( "/login/cbox/public_page/"+cbchecked );
        spinner.remove();
  });



//delete confirmation
$( "#deletedialog" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.45), modal: true });
$( "#deleteopener" ).click(function() {  $( "#deletedialog" ).dialog( "open" ); });
$( "#deletedialog .close_dialog" ).click(function() {  $( "#deletedialog" ).dialog( "close" ); });

//generic dialog, one per page
$( "#justadialog" ).dialog({ autoOpen: false, width: 360, modal: true });
$( "#justanopener" ).click(function() {  $( "#justadialog" ).dialog( "open" ); });
$( "#justadialog .close_dialog" ).click(function() {  $( "#justadialog" ).dialog( "close" ); });

//image delete confirmation
$( "#imgdeldlg" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.45), modal: true });
$( "#imgdeldlg .close_dialog" ).click(function() {  $( "#imgdeldlg" ).dialog( "close" ); });


//add proxy car

function findProxyCar() {
  var car_plates_or_vin = $("#car_plates_or_vin").val();
  var auth_car_list = $("#auth_car_list").val();
  $('#cars_by_owner').load('/car/car_list', {"car_plates_or_vin":car_plates_or_vin, "auth_car_list":auth_car_list });
}

$( "#find_cars_submit" ).click(function() {  findProxyCar();        });
$("#car_plates_or_vin").on('keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) {  e.preventDefault();       findProxyCar();  }
});

//add authorised user to your car

function addAuthUser() {
  var car_id = $("#car_id").val();
  var user_name = $("#service_provider_name").val();
  $('#auth_user_result').load('/car/add_auth_usr', {"auth_usr_name":user_name, "car_id":car_id });
    $("#service_provider_name").val('');
}
$( "#add_auth_user_submit" ).click(function(e) {  e.preventDefault(); addAuthUser();        });
$("#service_provider_name").on('keypress', function(e) {//on 'keyup keypress' fires twice
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) {
    e.preventDefault();
    addAuthUser();  }
});

$( "#authusrremovedlg" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.45), modal: true });
$('body').on('click', '.authusrremovedlgopener', function() {
  $("#authusrtoremove").html($(this).data('authusr'));
  $( "#authusrremovedlg" ).dialog( "open" );
  });
$( "#authusrremovedlg .close_dialog" ).click(function() {  $( "#authusrremovedlg" ).dialog( "close" ); });
$( "#authusrremovebtn" ).click(function(e) {
  e.preventDefault();
  var car_id = $("#car_id").val();
  var user_name = $("#authusrtoremove").html();
  $('#auth_user_result').load('/car/remove_auth_usr', {"auth_usr_name":user_name, "car_id":car_id });
  $( "#authusrremovedlg" ).dialog( "close" );
                               });


//transfer car to another user
//show users when typing
$('#new_owner_name_or_email').on('keyup', function () {
  if ($('#new_owner_name_or_email').val().length  > 2) {
    $('#new_user_result').load('/car/find_usr', {"usr_name_or_email": $('#new_owner_name_or_email').val() });
  }
    });

//fill search box with user name
$('body').on('click', '.transferusrlink', function(e) {
  e.preventDefault();
  $('#new_owner_name_or_email, #service_provider_name').val($(this).attr('href'));
});

//same for adding authusers
$('#service_provider_name').on('keyup', function () {
  if ($('#service_provider_name').val().length  > 2) {
    $('#auth_user_suggestion').load('/car/find_usr', {"usr_name_or_email": $('#service_provider_name').val() });
  }
    });





//event gallery

function changePic(caller) {
  $('#heroimg').addClass('muted');
  $("#heroimgcenter" ).html('<div class="icon-spin3 spinner"> </div>');
  $('#heroimg').attr('src', caller.children('a').data('url')).load(function() {
  $('#heroimg-container').children('a').attr("href", caller.children('a').data('url'));
  $("#heroimgcenter" ).html('');
  $('#heroimg').removeClass('muted');
  $('.imgbutton').removeClass('active');
  caller.addClass('active');

    });
}

function changeFSPic(caller) {
  $('#fsimg img').addClass('muted');
  $("#fsimgcenter" ).html('<div class="icon-spin3 spinner"> </div>');
  $('#fsimg').data('ord', caller.children('a').data('ord'));
  $('#fsimg img').attr('src', caller.children('a').data('url')).load(function() {
  $("#fsimgcenter" ).html('');
  $('#fsimg img').removeClass('muted');
  $('.imgbutton').removeClass('active');
  caller.addClass('active');

    });
}

function slidePicStrip(amount) {
  var existingMargin = Math.abs(parseInt($('#fspicstrip').css( "margin-left" )));
  var newMargin;
  if (amount > 0) {
  newMargin = existingMargin + (stripPicWidth * amount);
                } else {
  newMargin = existingMargin - (stripPicWidth * Math.abs(amount));
                }



    var totalPics = $("#picstrip a").length;
   if (newMargin < ((totalPics * stripPicWidth) - (fsPicStripWidth - stripPicWidth)  )) {  //check if we're not at the end of strip
                  $('#fspicstrip').animate({ marginLeft: '-'+ newMargin +'px' }, 500);
   }
}

$('body').on('click', '#heroimg-link', function(e) {
  e.preventDefault();
  /*$( "#event-view" ).dialog( "close" ); */
  $( "#previous-event-link" ).hide();
  $( "#next-event-link" ).hide();
  if (parseInt($('#piccount').html()) > 1) {
    $('#fsprev').show(); $('#fsnext').show();
  }
  $('#fs').addClass('fs');
  $('#fs').removeClass('display-none');
  $('#fsimg img').attr("src", $(this).attr('href'));
  var thisord = $('#picstrip .imgbutton.active').children('a').data('ord');
  var totalPics = $("#picstrip a").length;
  fsPicStripWidth = $("#fspicstrip").width();
  $('#fsimg').data('ord', thisord );
  $('#picstrip .imgbutton').removeClass('active');
  $('#fspicstrip .imgbutton').find("[data-ord='" + thisord + "']").parent().addClass('active');
  if ((totalPics * stripPicWidth) > fsPicStripWidth ) {
  $('#fspsprev').show(); $('#fspsnext').show();
}
});

$('body').on('click', '#fspsnext', function() {
  slidePicStrip(1);
});

$('body').on('click', '#fspsprev', function() {
  slidePicStrip(-1);
});

$('body').on('click', '#fsclose', function(e) {
  e.preventDefault();
  $('#fs').removeClass('fs');
  $('#fs').addClass('display-none');
  showEventNavArrows();
});

$('body').on('click', '#fsnext', function(e) {
  e.preventDefault();
  var current = $('#fsimg').data('ord');
  var next = current + 1;
  if (!$('#fspicstrip .imgbutton').find("[data-ord='" + next + "']").length  )
     {next = 1;}
     var url = $('#fspicstrip .imgbutton').find("[data-ord='" + next + "']").data('url');
  $('#fsimg img').addClass('muted');
  $("#fsimgcenter" ).html('<div class="icon-spin3 spinner"> </div>');
  $('#fsimg').data('ord', next);
  $('#fsimg img').attr('src', url).load(function() {
  $("#fsimgcenter" ).html('');
  $('#fsimg img').removeClass('muted');
  $('#fspicstrip .imgbutton').removeClass('active');
  $('#fspicstrip .imgbutton').find("[data-ord='" + next + "']").parent().addClass('active');
     });

});


$('body').on('click', '#fsprev', function(e) {
  e.preventDefault();
  var current = $('#fsimg').data('ord');
  var prev = current - 1;
  if (prev < 1) {prev = parseInt($('#piccount').html());}

  var url = $('#fspicstrip .imgbutton').find("[data-ord='" + prev + "']").data('url');
  $('#fsimg img').addClass('muted');
  $("#fsimgcenter" ).html('<div class="icon-spin3 spinner"> </div>');
  $('#fsimg').data('ord', prev);
  $('#fsimg img').attr('src', url).load(function() {
  $("#fsimgcenter" ).html('');
  $('#fsimg img').removeClass('muted');
  $('#fspicstrip .imgbutton').removeClass('active');
  $('#fspicstrip .imgbutton').find("[data-ord='" + prev + "']").parent().addClass('active');
     });

});

$('body').on('click', '#picstrip .imgbutton', function(e) {
  e.preventDefault();
  var caller = $(this);
  changePic(caller);
});

$('body').on('click', '#fspicstrip .imgbutton', function(e) {
  e.preventDefault();
  var caller = $(this);
  changeFSPic(caller);
});

/* ==========================mouseover on hover in full screen
$('body').on({
  mouseenter: function() {
  var caller = $(this);
  changeFSPic(caller);
  },
  mouseleave: function() {}
  }, '#fspicstrip .imgbutton'
  ); */

$('body').on({
  mouseenter: function() {
  var caller = $(this);
  changePic(caller);
  },
  mouseleave: function() {}
  }, '#picstrip .imgbutton'
  );

/*/*$('#fspicstrip .imgbutton').hover(function() {
  var caller = $(this);
  changeFSPic(caller);
});

$('#picstrip .imgbutton').hover(function() {
  var caller = $(this);
  changePic(caller);
});*/

// event view
/*
$( "#event-view" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.85), modal: true, position: { my: "center top", at: "center top", of: ".container" } });
$( ".event-opener" ).click(function(e) {
    e.preventDefault();
    var event_id = $(this).data("event_id");
    var event_title = $(this).data("event_title");

    $( "#event-view" ).load( "/car/eventm/"+ event_id);
  $( "#event-view" ).dialog( "open" );
  $( "#event-view" ).dialog( "option", "title", event_title );
    $( "#event-view" ).html('<div class="icon-spin3 spinner"> </div>');


});*/

/* event navigation back and forth*/

function showEventNavArrows() {
  if ( typeof event_car_id !== 'undefined' && event_id !== 'undefined' ) {
$.getJSON( "/car/neighboring_events/"+event_car_id+"/"+event_id, function( data ) {
  var nexturl = "/car/event/"+data.next;
  var prevurl = "/car/event/"+data.prev;
//data.prev; data.next; data.current_no;
$("#previous-event-link a").attr("href", prevurl);
$("#next-event-link a").attr("href", nexturl);
if (parseInt(data.prev) !== 0) {$( "#previous-event-link" ).show();}
if (parseInt(data.next) !== 0) {$( "#next-event-link" ).show();}
});
  }
}



showEventNavArrows();



/*  event filter by type in event index pg */

if ( typeof event_types_present !== 'undefined') {
  $("#event-types-present").removeClass('hide');
  $.each(event_types_present, function(key, entry) {
    $("#event-types-present").append('<a href="#" data-type="'+entry+'" class="smallish bold kcms mr1 evfilter">['+key+']</a>');
  });
}

$('body').on('click', '.evfilter', function(e) {
  e.preventDefault();
  var tag = $(this).data("type");
  $(".event").hide();
  $("."+tag).show();
});

$('body').on('click', '.evfltreset', function(e) {
  e.preventDefault();
  $(".event").show();
});

/* context menu opener */
$( ".context_menu_opener" ).click(function(e) {
    var element = $(this).data("element");
    if ($(".closeonclick:visible").length) {    $( ".closeonclick:not(#" + element + ")" ).hide("fast");  }
    $("#"+element).toggle("fast");
    e.stopPropagation();
    });


$( ".set_reminder" ).click(function() {
    $("#remindertoggle").val('on');
    $("#justanopener > .icon-bell-alt").addClass("red");
    $("#reminder_content").val( $("#reminder_content_proxy").val()  );
    $( "#justadialog" ).dialog( "close" );  });
$( ".clear_reminder" ).click(function() {
    $("#remindertoggle").val('off');
    $("#justanopener > .icon-bell-alt").removeClass("red");
    $( "#justadialog" ).dialog( "close" );    });

$('#reminder_time_offset').on('change', function () {
    var time_offset = this.value;
    var time_offset_timestamp = $("#reminder_time_offset option:selected").data('timestamp');
$('#reminder_time').val(time_offset);

$('#timestampdate').val(time_offset_timestamp);
  console.log(time_offset_timestamp)  ;
} );


$( "#request_car_access_dlg" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.75), modal: true });
$( "#request_car_access_dlg .close_dialog" ).click(function() {  $( "#request_car_access_dlg" ).dialog( "close" ); });
$('body').on('click', '.request_car_access_dlgopnr', function() {
    $("#owner_distance").html('');
    $("#owner_distance").load('/car/owner_distance', {"owner_id":$(this).data('owner') });
    $( "#request_car_access_dlg" ).dialog( "open" );
    $( "#request_car_access_dlg #car_owner" ).val($(this).data('owner'));
    $( "#request_car_access_dlg #car_id" ).val($(this).data('id'));
    $( "#request_car_access_dlg .request_car_access" ).data('id', $(this).data('id'));
    });

//request to be authorised user on someone elses car
$('body').on('click', '.request_car_access', function() {
    $('.requestparent[data-car_id="' + $("#car_id").val() + '"]').load('/car/request_auth', {"car_id":$("#car_id").val() });
  });


$('body').on({
  mouseenter: function() {
    $('#fspicstrip').fadeIn();
  },
  mouseleave: function() {
    setTimeout(function () { $('#fspicstrip').fadeOut(); }, 1500);
  }
  }, '#fspscont'
  );

//car data xml edit

$('.databittxt').click(function() {    $(this).addClass( "field " );    });
$('.databittxt').blur(function() {    $(this).removeClass( "field " );    });

$('.attrtxt').click(function() { $(this).addClass( "field " ); });
$('.attrtxt').blur(function() { $(this).removeClass( "field " ); });

function writeAttrTxt(key, val, chapter, entry, unit, validate) {
  var car_id = $("#car_id").val();
      $('.response').html('<div class="icon-spin3 spinner"> </div>');
      $( ".attrtxt, .attrunit" ).prop( "disabled", true ); //disable all inputs while we get the response
      $('.response').load(
            '/car/write_attr',
            {"key":key, "value":val, "car_id":car_id, "chapter":chapter, "entry":entry, "unit":unit, "validate":validate  },
            function() {
                       var response = $('.response').html();
                       if (response == 'false') {
                       $('.response').html('<i class="icon-cancel red"> </i>');
                       } else {
                       $('.response').html('<i class="icon-ok green"> </i>');
                       $('#' + key + '.attrtxt' ).val(response);
                       }
                       $( ".attrtxt, .attrunit" ).prop( "disabled", false );
     });
}

$(".attrtxt").on('change', function() {
    key = $(this).attr('id');
    val = $(this).val();
    chapter = $(this).data('chapter');
    entry = $(this).data('entry');
    validate  = $(this).data('validate');
    unit = '';
    if ( $( '.attrunit[data-id="' + key + '"]' ).length ) {        unit = $( '.attrunit[data-id="' + key + '"]' ).val();    }
    writeAttrTxt(key, val, chapter, entry, unit, validate);
   //console.log(key+' : '+val+' : '+chapter+' : '+entry+' : '+unit+' : '+validate);
    });


$(".attrunit").on('change', function() {
    unit = $(this).val();
    key = $(this).data('id');
    databittxt = $( '#' + key );
    if (databittxt.length) {
    val = databittxt.val();
    chapter = databittxt.data('chapter');
    entry = databittxt.data('entry');
    validate  = databittxt.data('validate');
    if ($( this ).hasClass( "unit-inches" )) {databittxt.val(convertLength(unit, val));  val = databittxt.val();}
    if ($( this ).hasClass( "unit-millimeters" )) {databittxt.val(convertLength(unit, val));  val = databittxt.val();}
    if ($( this ).hasClass( "unit-volume" )) {databittxt.val(convertVolume(unit, val));  val = databittxt.val();}
    if ($( this ).hasClass( "unit-power" )) {databittxt.val(convertPower(unit, val));  val = databittxt.val();}
    if ($( this ).hasClass( "unit-weight" )) {databittxt.val(convertWeight(unit, val));  val = databittxt.val();}
    writeAttrTxt(key, val, chapter, entry, unit, validate);
        }
    });


function editXMLDataBits(key, val, chapter, chapterno, entry, unit, validate) {
  var car_id = $("#car_id").val();
      $('.response[data-key="' + key + '"]').html('<div class="icon-spin3 spinner"> </div>');
      $( ".databittxt, .databitunit, .databitcustval, .databitcustname" ).prop( "disabled", true ); //disable all inputs while we get the response
      $('.response[data-key="' + key + '"]').load(
            '/car/edit_xml_car_data_bits',
            {"key":key, "value":val, "car_id":car_id, "chapter":chapter, "chapterno":chapterno, "entry":entry, "unit":unit, "validate":validate  },
            function() {
                       var response = $('.response[data-key="' + key + '"]').html();
                       if (response == 'false') {
                       $('.response[data-key="' + key + '"]').html('<i class="icon-cancel red"> </i>');
                       } else {
                       $('.response[data-key="' + key + '"]').html('<i class="icon-ok green"> </i>');
                       if (chapterno == 'NEW') { window.location.reload(); }
                       $('#' + key + '.databittxt' ).val(response);
                       }
                       $( ".databittxt, .databitunit, .databitcustval, .databitcustname" ).prop( "disabled", false );
     });
}

$(".databittxt").on('change', function() {
    key = $(this).attr('id');
    val = $(this).val();
    chapter = $(this).data('chapter');
    chapterno = $(this).data('chapterno');
    entry = $(this).data('entry');
    validate  = $(this).data('validate');
    unit = '';
    if ( $( '.databitunit[data-id="' + key + '"]' ).length ) {        unit = $( '.databitunit[data-id="' + key + '"]' ).val();    }
    editXMLDataBits(key, val, chapter, chapterno, entry, unit, validate);
    });

$(".databitunit").on('change', function() {
    unit = $(this).val();
    key = $(this).data('id');
    databittxt = $( '#' + key );
    if (databittxt.length) {
    val = databittxt.val();
    chapter = databittxt.data('chapter');
    chapterno = databittxt.data('chapterno');
    entry = databittxt.data('entry');
    validate  = databittxt.data('validate');
    if ($( this ).hasClass( "unit-inches" )) {databittxt.val(convertLength(unit, val));  val = databittxt.val();}
    if ($( this ).hasClass( "unit-millimeters" )) {databittxt.val(convertLength(unit, val));  val = databittxt.val();}
    if ($( this ).hasClass( "unit-volume" )) {databittxt.val(convertVolume(unit, val));  val = databittxt.val();}
    if ($( this ).hasClass( "unit-power" )) {databittxt.val(convertPower(unit, val));  val = databittxt.val();}
    if ($( this ).hasClass( "unit-weight" )) {databittxt.val(convertWeight(unit, val));  val = databittxt.val();}
    editXMLDataBits(key, val, chapter, chapterno, entry, unit, validate);
        }
    });

$(".databitcustval").on('change', function() {
        key = $(this).attr('id');
    if ( $( '.databitcustname[data-id="' + key + '"]' ).val().length > 2 )
    {
        val = $(this).val();
        chapter = $(this).data('chapter');
        chapterno = $(this).data('chapterno');
        entry = $( '.databitcustname[data-id="' + key + '"]' ).val();
        validate  = $(this).data('validate');
        unit = ''; //future use, maybe?
        addXMLDataBits(key, val, chapter, chapterno, entry, unit, validate);
    }
});

function addXMLDataBits(key, val, chapter, chapterno, entry, unit, validate) {
  var car_id = $("#car_id").val();
      $('.response[data-key="' + key + '"]').html('<div class="icon-spin3 spinner"> </div>');
      $( ".databittxt, .databitunit, .databitcustval, .databitcustname" ).prop( "disabled", true ); //disble all inputs while we get the response
      $('.response[data-key="' + key + '"]').load(
            '/car/add_xml_car_data_bits',
            {"key":key, "value":val, "car_id":car_id, "chapter":chapter, "chapterno":chapterno, "entry":entry, "unit":unit, "validate":validate  },
            function() {
                       var response = $('.response[data-key="' + key + '"]').html();
                       if (response == 'false') {
                       $('.response[data-key="' + key + '"]').html('<i class="icon-cancel red"> </i>');
                       } else {
                       $('.response[data-key="' + key + '"]').html('<i class="icon-ok green"> </i>');
                       $('#' + key + '.databitcustval' ).val(response);
                       }
                       $( ".databittxt, .databitunit, .databitcustval, .databitcustname" ).prop( "disabled", false );
                       window.location.reload();
     });
}


function convertLength(convertTo, value) {
    var response = 0;
    var number = Number(value.replace(',', '.'));
    if (convertTo == 'in') {        response = number / 25.4;    }
    if (convertTo == 'mm') {        response = number * 25.4;    }
    return response;
}

function convertVolume(convertTo, value) {
    var response = 0;
    var number = Number(value.replace(',', '.'));
    if (convertTo == 'cm3') {        response = number * 16.3871;    }
    if (convertTo == 'ci') {        response = number * 0.0610237;    }
    response = +response.toFixed(2); return response;
}

function convertPower(convertTo, value) {
    var response = 0;
    var number = Number(value.replace(',', '.'));
    if (convertTo == 'kW') {        response = number * 0.7457;    }
    if (convertTo == 'HP') {        response = number * 1.34102;    }
    response = +response.toFixed(2);
        return response;
}

function convertWeight(convertTo, value) {
    var response = 0;
    var number = Number(value.replace(',', '.'));
    if (convertTo == 'kg') {        response = number * 0.453592;    }
    if (convertTo == 'lbs') {        response = number * 2.20462;    }
    response = +response.toFixed(2); return response;
}


$( ".databit-date" ).datepicker({
     dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true,
      yearRange: "c-20:c",
      maxDate: 1
    });

$( ".expiry-date" ).datepicker({
     dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true,
      minDate: 1,
      defaultDate: +365
    });

$( "#event_date_proxy" ).datepicker({
      changeMonth: true,
      changeYear: true,
      altField: "#event_date",
      altFormat: "@",
      yearRange: "c-20:c",
      maxDate: 1
    });


$( ".deletedatabitdlg" ).click(function() {
    key = $(this).closest('span').data('key');
    databittxt = $( '#' + key );
    value = databittxt.val();
    chapter = databittxt.data('chapter');
    chapterno = databittxt.data('chapterno');
    entry = databittxt.data('entry');
    validate  = databittxt.data('validate');
    unit = '';
    $('#key').val(key);
    $('#val').val(value);
    $('#chapter').val(chapter);
    $('#chapterno').val(chapterno);
    $('#entry').val(entry);
    $('#unit').val(unit);
    $('#validate').val(validate);

  //  console.log(key+' '+unit+' '+chapter+' '+chapterno+' '+entry+' '+value+' '+validate);

    $( "#deletedialog" ).dialog( "open" );
    });



$('body').on('click', '.imgdel', function(e) {
  e.preventDefault();
  var img =  $(this).closest('.portlet').attr('id');
  var no =  $(this).closest('.portlet').data('number');

    $( "#imgdeldlg" ).dialog( "open" );
$('#image_id').val(img);
  $('#image_no').val(no);

});

$('#imgdeldlg .delete').click(function() {
    var img = $('#image_id').val();
    var no = $('#image_no').val();

    var usrimg = $('#user_images').val();
  var usrimg_array = usrimg.split(',');
  var index = usrimg_array.indexOf(img);
    if (index > -1) {
    usrimg_array.splice(index, 1);
    usrimg = usrimg_array.join();
    $('#user_images').val(usrimg);
    }

 $( '.portlet[data-number="'+no+'"]' ).load( '/car/imgedit', {
    "task":"del",
    "owner":$('#owner').val(),
    "car_id":$('#car_id').val(),
    "chapter":$('#chapter').val(),
    "img":img,
    "user_images":usrimg,
    "event_time":$('#event_time').val(),
    "event_microtime":$('#event_microtime').val(),
    "wherefrom": $('#wherefrom').val()
   }, function() {
      var response = $( '.portlet[data-number="'+no+'"]' ).html();
      if (response == 'false') {
      $( '.portlet[data-number="'+no+'"]' ).html('<i class="icon-cancel red"> </i>');
      } else {
      $( '.portlet[data-number="'+no+'"]' ).hide('fast');
      }
    });

});


$('body').on('click', '.imgrotate', function(e) {
  e.preventDefault();
  var img =  $(this).closest('.portlet').attr('id');
  var no =  $(this).closest('.portlet').data('number');
  var usrimg = $('#user_images').val();
  var owner = $('#owner').val();
  var car_id = $('#car_id').val();
  var direction = 'cw';
  if ($(this).hasClass( "imgccw" )) {direction = 'ccw';}

    $( '.portlet[data-number="'+no+'"] .portlet-content img ' ).attr("src", "/images/spinner.gif" );
    $( '#response' ).load( '/car/imgedit', {
    "task":direction,
    "owner":owner,
    "chapter":$('#chapter').val(),
    "car_id":car_id,
    "img":img,
    "user_images":usrimg,
     "event_time":$('#event_time').val(),
    "event_microtime":$('#event_microtime').val(),
    "wherefrom": $('#wherefrom').val()
   }, function() {
      var response = $( '#response' ).html();
      if (response == 'false') {
      $( '.portlet[data-number="'+no+'"] ' ).html('<i class="icon-cancel red"> </i>');
      } else {
        $( '.portlet[data-number="'+no+'"] ' ).attr('id', response);
  var usrimg_array = usrimg.split(',');
  var index = usrimg_array.indexOf(img);
    if (index > -1) {
    usrimg_array[index] = response;
    usrimg = usrimg_array.join();
    $('#user_images').val(usrimg);
    }

        var imgurl = '/car/image/'+car_id+'/'+response+'/120';
        $( '.portlet[data-number="'+no+'"] .portlet-content img ' ).attr("src", imgurl );
      }
    });


});

$("#odo_warning_form").submit(function( e ) {
console.log('klik');
   if  ((parseInt($('#this_event_odo').val()) >= parseInt($('#this_car_odo').val() )) || ($('#odo_warning_checkbox').prop('checked'))) {
    return;
   }
   $('#odo_warning_box').removeClass('hide');
    e.preventDefault();

});



//after everythin else loads, inhabit the little boxes

$(window).load(function() {
    if ($('#carindex_my_car_list').length) {
         $( '#carindex_my_car_list' ).load( '/car/my_car_list');
          $( "#carindex_my_car_list" ).removeClass('hide');
    }


    if ($('#carindex_expiry_list').length) {
         $( '#carindex_expiry_list' ).load( '/car/expiry_list/'+$("#car_id").val());
    }
});

$( ".nestedlink" ).click(function(e) {
    e.preventDefault();
window.location.href = $(this).data('href');
} );

//full size image display
$('body').on('click', '.fsgalimg-link', function(e) {
  e.preventDefault();

  if (parseInt($('#piccount').html()) > 1) {
    $('#fsprev').show(); $('#fsnext').show();
  }
  $('#fs').addClass('fs');
  $('#fs').removeClass('display-none');
  $('#fsimg').children('img').attr("src", $(this).attr('href'));
  var thisord = $('#picstrip .imgbutton.active').children('a').data('ord');
  var totalPics = $("#picstrip a").length;
  fsPicStripWidth = $("#fspicstrip").width();
  $('#fsimg').data('ord', thisord );
  $('#picstrip .imgbutton').removeClass('active');
  $('#fspicstrip .imgbutton').find("[data-ord='" + thisord + "']").parent().addClass('active');
  if ((totalPics * stripPicWidth) > fsPicStripWidth ) {
  $('#fspsprev').show(); $('#fspsnext').show();
}
});

// add new car by vin
$( "#submit_vin" ).click(function() {
    getVinData($('#new_vin').val());
    });

$('body').on('click', '#retest_vin', function(e) {
       e.preventDefault();
    getVinData($('#new_vin').val());
    });

$("#new_vin").on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) {
    e.preventDefault();
 getVinData($('#new_vin').val());
  }
});

function getVinData(vin) {
    if (vin.length == 17) {
        $( "#new_car_data_by_vin" ).html('<div class="icon-spin3 spinner"> </div>' +  $( "#new_car_data_by_vin" ).data('body'));
        $( "#new_car_data_by_vin" ).load( '/car/new_vin_data/'+vin );
        $( "#submit_vin_container" ).hide();

        }
}

$('body').on('click', '.visibility_setting_private', function(e) {
    e.preventDefault();
    var event = $(this).data('event');
    $( '.visibility_setting_private[data-event="'+event+'"] a i' ).addClass('gray');
    $.ajax({
        url: '/car/make_event_public/'+event,
        dataType: "text",
        success: function(data) {
        if (data == 'true') {
            $( '.visibility_setting_private[data-event="'+event+'"] a i' ).removeClass('gray');
            $( '.visibility_setting_private[data-event="'+event+'"]' ).addClass('hide');
            $( '.visibility_setting_public[data-event="'+event+'"]' ).removeClass('hide');
            $( '.event[data-event="'+event+'"]' ).addClass('public-event');
        }
        }
    });

    });

$('body').on('click', '.visibility_setting_public', function(e) {
    e.preventDefault();
    var event = $(this).data('event');
    $( '.visibility_setting_public[data-event="'+event+'"] a i' ).addClass('gray');
    $.ajax({
        url: '/car/make_event_private/'+event,
        dataType: "text",
        success: function(data) {
        if (data == 'true') {
            $( '.visibility_setting_public[data-event="'+event+'"] a i' ).removeClass('gray');
            $( '.visibility_setting_public[data-event="'+event+'"]' ).addClass('hide');
            $( '.visibility_setting_private[data-event="'+event+'"]' ).removeClass('hide');
                        $( '.event[data-event="'+event+'"]' ).removeClass('public-event');
            }
        }
        });
     });


$('.exptxt').click(function() { $(this).addClass( "field " ); });
$('.exptxt').blur(function() { $(this).removeClass( "field " ); });

function writeExpiry(key, val, chapter, entry, validate, siblings) {
  var car_id = $("#car_id").val();
      $('.response').html('<div class="icon-spin3 spinner"> </div>');
      $( ".exptxt" ).prop( "disabled", true ); //disable all inputs while we get the response
      $('.response').load(
            '/car/write_expiry',
            {"key":key, "value":val, "car_id":car_id, "chapter":chapter, "entry":entry, "validate":validate, "siblings":siblings  },
            function() {
                       var response = $('.response').html();
                       if (response == 'false') {
                       $('.response').html('<i class="icon-cancel red"> </i>');
                       } else {
                       $('.response').html('<i class="icon-ok green"> </i>');
                       $('#' + key + '.exptxt' ).val(response); //if input
                       $('#' + key + '.exptxt' ).html(response); //if plain div
                       }
                       $( ".exptxt" ).prop( "disabled", false );
     });
}

$(".exptxt").on('change', function() {
    key = $(this).attr('id');
    val = $(this).val();
    chapter = $(this).data('chapter');
    entry = $(this).data('entry');
    validate  = $(this).data('validate');
    var siblings = $('*[data-chapter="' + chapter + '"]').map(function() {
    var rObj = {};
    if ($(this).val())
    {
    rObj[$(this).data('entry')] = $(this).val();
    } else {
    rObj[$(this).data('entry')] = $(this).html();
    }
    return rObj;
    }).get();
    writeExpiry(key, val, chapter, entry, validate, JSON.stringify(siblings));
    //console.log(key+' : '+val+' : '+chapter+' : '+entry+' : '+validate+' : '+JSON.stringify(siblings));
    });

    $(".expchk").on('change', function() {
        const val = ($(this).prop('checked')) ? 'V' : '0';
        const chapter = $(this).data('chapter');
        const car_id = $("#car_id").val();
        $( ".exptxt, .expchk" ).prop( "disabled", true );
        $('.response').load(
              '/car/write_expiry_status',
              {"car_id":car_id, "expiry":chapter, "status":val  },
              function() {
                         var response = $('.response').html();
                         if (response == 'false') {
                         $('.response').html('<i class="icon-cancel red"> </i>');
                         } else {
                         $('.response').html('<i class="icon-ok green"> </i>');
                         }
                         $( ".exptxt, .expchk" ).prop( "disabled", false );
              });
        });



  $('.autoheight').on('keyup',function(){
  $(this).css('height','auto');
  $(this).css('overflow','hidden');
  $(this).height(this.scrollHeight);
});


$(".car-meta").on('change', function() { /*for checkbox*/
    var element = $(this).attr('id');
    var val = $(this).val();
    var key = $(this).data('key');
    var checked = $(this).prop('checked');
    var car_id = $("#car_id").val();
    writeCarMeta(car_id, element, key, val, checked, '.response');
    });


function writeCarMeta(car_id, element, key, val, checked, responseel) { /*for checkbox*/
      $(responseel).html('<div class="icon-spin3 spinner"> </div>');
//      console.log(car_id+' : '+element+' : '+key+' : '+val+' : '+checked);
      $("#"+element).prop( "disabled", true );
      var value = '';
      if (checked) value = val;
      $(responseel).load(
            '/car/write_car_meta',
            {"car_id":car_id, "meta_key":key, "meta_value":value  },
            function() {
                       var response = $(responseel).html();
                       if (response == 'false') {
                       $(responseel).html('<i class="icon-cancel red"> </i>');
                       } else {
                       $(responseel).html('<i class="icon-ok green"> </i>');
                       }
                       $( "#"+element ).prop( "disabled", false );
     });
}

$("#set_car_public").on('change', function() {
    var checked = $(this).prop('checked');
    var car_id = $("#car_id").val();
    var enable_car_access = '';
    var disable_car_access = '';
    if (checked) {enable_car_access = car_id;} else {disable_car_access = car_id;}
    $("#set_car_public").prop( "disabled", true );
    $(".response").load(
          '/car/change_car_access',
          {"car_id":car_id, "enable_car_access":enable_car_access, "disable_car_access":disable_car_access  },
          function() {
                     var response = $(".response").html();
                     if (response == 'false') {
                     $(".response").html('<i class="icon-cancel red"> </i>');
                     } else {
                     $(".response").html('<i class="icon-ok green"> </i>');
                      if (checked) {
                        $("#car_public_settings").show();
                        $(".car_public_msg_pub").show();
                        $(".car_public_msg_priv").hide();
                      } else {
                        $("#car_public_settings").hide();
                        $(".car_public_msg_pub").hide();
                        $(".car_public_msg_priv").show();
                      }
                     }
           $( "#set_car_public" ).prop( "disabled", false );
   });


    });

    $(".car-meta-txt").on('change', function() { /*for textfield*/
        var element = $(this).attr('id');
        var val = $(this).val();
        var key = $(this).data('key');
        var car_id = $("#car_id").val();
        writeCarMetaText(car_id, element, key, val, '.response');
        });


    function writeCarMetaText(car_id, element, key, val, responseel) { /*for textfield*/
          $(responseel).html('<div class="icon-spin3 spinner"> </div>');
          //console.log(car_id+' : '+element+' : '+key+' : '+val);
          $("#"+element).prop( "disabled", true );
          $(responseel).load(
                '/car/write_car_meta',
                {"car_id":car_id, "meta_key":key, "meta_value":val  },
                function() {
                           var response = $(responseel).html();
                           if (response == 'false') {
                           $(responseel).html('<i class="icon-cancel red"> </i>');
                           } else {
                           $(responseel).html('<i class="icon-ok green"> </i>');
                           }
                           $( "#"+element ).prop( "disabled", false );
         });
    }

    $('#oil-change-interval').on('change', function() {
      var oil_interval = parseInt($(this).val());
      var next_change = parseInt($("#next-oil-change").html());
      var old_oil_interval = parseInt($("#saved_oil_interval").val());
      $("#next-oil-change").html(next_change - old_oil_interval + oil_interval);
    });


    $('#distr-change-interval').on('change', function() {
      var distr_interval = parseInt($(this).val());
      var next_change = parseInt($("#next-distr-change").html());
      var old_distr_interval = parseInt($("#saved_distr_interval").val());
      $("#next-distr-change").html(next_change - old_distr_interval + distr_interval);
    });

    $(".exptxtsp").on('change', function() {
        var key = $(this).attr('id');
        var val = parseInt($(this).val());
        var chapter = $(this).data('chapter');
        var entry = $(this).data('entry');
        var validate  = $(this).data('validate');
        var current_odo = parseInt($("#current_odo").val());
        var car_id = $("#car_id").val();
        var oil_interval = parseInt($("#oil-change-interval").val());
        var distr_interval = parseInt($("#distr-change-interval").val());
        var responseel = '.response';
        if ($(this).hasClass( "oilchange" )) {
          if (val > current_odo) {
            $(responseel).load(
                  '/car/save_odo_ajax',
                  {"this_car_id":car_id, "this_event_odo":val  },
                  function() {
                             var response = $(responseel).html();
                             if (response == 'false') {
                             $(responseel).html('<i class="icon-cancel red"> </i>');
                             } else {
                             $(responseel).html('<i class="icon-ok green"> </i>');
                             }

           });
           $("#next-oil-change").html(val+oil_interval);
          }
        }
        if ($(this).hasClass( "distrbelt" )) {
          if (val > current_odo) {
            $(responseel).load(
                  '/car/save_odo_ajax',
                  {"this_car_id":car_id, "this_event_odo":val  },
                  function() {
                             var response = $(responseel).html();
                             if (response == 'false') {
                             $(responseel).html('<i class="icon-cancel red"> </i>');
                             } else {
                             $(responseel).html('<i class="icon-ok green"> </i>');
                             }

           });
           $("#next-distr-change").html(val+distr_interval);
          }
        }

        var siblings = $('*[data-chapter="' + chapter + '"]').map(function() {
        var rObj = {};
        if ($(this).val())
        {
        rObj[$(this).data('entry')] = $(this).val();
        } else {
        rObj[$(this).data('entry')] = $(this).html();
        }
        return rObj;
        }).get();
        writeExpiry(key, val, chapter, entry, validate, JSON.stringify(siblings));
        //console.log(key+' : '+val+' : '+chapter+' : '+entry+' : '+validate+' : '+JSON.stringify(siblings));
        });


        //event type chooser dialog
        $( ".evtypechooser" ).dialog({ autoOpen: true, width: 360, modal: true,  dialogClass: 'no-close', });
        $('body').on('click', '.evtypechooser .evtype', function(e) {
                e.preventDefault();
        var evtype_id = $(this).attr('href');
        var evtype_name = $(this).html();
        addEventTypeToList(evtype_id, evtype_name);
          $( ".evtypechooser" ).dialog( "close" );
        } );


        $("#timing_belt").on('change', function() {
            const checked = $(this).prop('checked');
            if (checked) {
            $( "#timing_belt_panel" ).removeClass('hide');
          } else {
            $( "#timing_belt_panel" ).addClass('hide');
          }
            });

            $("#new_oil").on('change', function() {
                const checked = $(this).prop('checked');
                const oil_interval = parseInt($("#oil_interval").val());
                const event_odo = parseInt($("#event_odo").val());
                if (checked) {
                $("#next-oil-change").val(event_odo + oil_interval);
              } else {
                $("#next-oil-change").val('');
              }
                });

                $('#oil_interval').on('change', function() {
                  const oil_interval = parseInt($(this).val());
                  const event_odo = parseInt($("#event_odo").val());
                $("#next-oil-change").val(event_odo + oil_interval);
                });

$( '.dropdown-toggle' ).click(function(e) {
    $(".dropdown-menu" ).toggle("fast");
    e.stopPropagation();
});
