var stripPicWidth = 116;

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
   
    if($('#user_to_auth').val()) {
    $( "#accordion" ).accordion({
      active: 3
      });
    } else {
      $( "#accordion" ).accordion();
    }
    var the_data_bit;
    var the_data_bit_key;
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
$( "#event_type_dialog" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.75), modal: true });
$( "#event_type_dialog_opener" ).click(function() {  $( "#event_type_dialog" ).dialog( "open" ); });

$( "#event_type_dialog .evtype" ).click(function(e) {
    e.preventDefault();
    var evtype_id = $(this).attr('href');
    var evtype_name = $(this).text();
$('#event_type').val(function(i, val) {
  return (val ? val + ',' : '') + evtype_id;      });
$('#taglisting').append("<li><span>"+evtype_name+"</span></li>");


$( "#event_type_dialog" ).dialog( "close" );   
} );

//new event dialog
$( "#new_event_dialog" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.85), modal: true });
$( "#new_event_dialog_opener" ).click(function() {  $( "#new_event_dialog" ).dialog( "open" ); });
  
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


//delete confirmation
$( "#deletedialog" ).dialog({ autoOpen: false, width: Math.floor($(window).width()*0.45), modal: true });
$( "#deleteopener" ).click(function() {  $( "#deletedialog" ).dialog( "open" ); });
$( "#deletedialog .close_dialog" ).click(function() {  $( "#deletedialog" ).dialog( "close" ); });

//add proxy car

function findProxyCar() {
  var car_owner = $("#car_owner_name").val();
  var auth_car_list = $("#auth_car_list").val();
  $('#cars_by_owner').load('/car/car_list', {"owner_name":car_owner, "auth_car_list":auth_car_list });
}

$( "#find_cars_submit" ).click(function() {  findProxyCar();        });
$("#car_owner_name").on('keypress', function(e) {
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

//request to be authorised user on someone elses car
$('body').on('click', '.request_car_access', function() {
 $(this).closest('.requestparent').load('/car/request_auth', {"car_id":$(this).data('id') }); 
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
                                  $("#"+element).toggle();
                                   e.stopPropagation();
                                  });

