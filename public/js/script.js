$(function(){





//******************************************************************************

//get the input and UL list

//$('#fileinput').on( "change", function() {
//  console.log( $( this ).text() );
//});


//var input = document.getElementById('fileinput');
//for every file...
//for (var x = 0; x < input.files.length; x++) {
	//add to list
//	var li = document.createElement('li');
//	li.innerHTML = 'File ' + (x + 1) + ':  ' + input.files[x].name;
//	list.append(li);
//}



//******************************************************************************
	
	var dropbox = $('#dropbox'),
  filebutton = $('#fileinput'), 
  car_id = $('#car_id').val(),
	message = $('.message', dropbox);
  
  /*
  $( "#new_event_form" ).submit(function( event ) {
  resetFormElement(filebutton);
});         */
  
  
  /*filebutton.change(function() {
  files = event.target.files;
  console.log(files);	
  })*/

        filebutton.fileklik({
        
        		// The name of the $_FILES entry:
		paramname:'pic',
		
		maxfiles: 5,
    	maxfilesize: 6,
		url: '/car/pic_upl?car_id='+car_id,
		
		uploadFinished:function(i,file,response){
			$.data(file).addClass('done');
      //prevent file from uploading again when the form is submitted:
     // $('#fileinput').addClass( 'uploaded' );
      $('#user_images').val(function(i,val) { 
     return val + (val ? ',' : '') + response.name;
      });
      
     // console.log(response);
			// response is the JSON object that post_file.php returns
		},
		
    	error: function(err, file) {
			switch(err) {
				case 'BrowserNotSupported':
					showMessage('Your browser does not support HTML5 file uploads!');
					break;
				case 'TooManyFiles':
					alert('Too many files!');
					break;
				case 'FileTooLarge':
					alert(file.name+' is too large!');
					break;
				default:
					break;
			}
		},
		
		// Called before each upload is started
		beforeEach: function(file){
			if(!file.type.match(/^image\//)){
				alert('Only images are allowed!');
				
				// Returning false will cause the
				// file to be rejected
				return false;
			}
		},
		
		uploadStarted:function(i, file, len){
			createImage(file);
		},
		
		progressUpdated: function(i, file, progress) {
			$.data(file).find('.progress').width(progress);
		}
        
        
         });
  
  
//*******************************************************************************  
  
  

	
	dropbox.filedrop({
		// The name of the $_FILES entry:
		paramname:'pic',
		
		maxfiles: 5,
    	maxfilesize: 6,
		url: '/car/pic_upl?car_id='+car_id,
		
		uploadFinished:function(i,file,response){
			$.data(file).addClass('done');
      $('#user_images').val(function(i,val) { 
     return val + (val ? ',' : '') + response.name;
      });
      
      console.log(response);
			// response is the JSON object that post_file.php returns
		},
		
    	error: function(err, file) {
			switch(err) {
				case 'BrowserNotSupported':
					showMessage('Your browser does not support HTML5 file uploads!');
					break;
				case 'TooManyFiles':
					alert('Too many files!');
					break;
				case 'FileTooLarge':
					alert(file.name+' is too large!');
					break;
				default:
					break;
			}
		},
		
		// Called before each upload is started
		beforeEach: function(file){
			if(!file.type.match(/^image\//)){
				alert('Only images are allowed!');
				
				// Returning false will cause the
				// file to be rejected
				return false;
			}
		},
		
		uploadStarted:function(i, file, len){
			createImage(file);
		},
		
		progressUpdated: function(i, file, progress) {
			$.data(file).find('.progress').width(progress);
		}
    	 
	});
	
	var template = '<div class="preview">'+
						'<span class="imageHolder">'+
							'<img />'+
							'<span class="uploaded"></span>'+
						'</span>'+
						'<div class="progressHolder">'+
							'<div class="progress"></div>'+
						'</div>'+
					'</div>'; 
	
	
	function createImage(file){

		var preview = $(template), 
			image = $('img', preview);
			
		var reader = new FileReader();
		
		image.width = 100;
		image.height = 100;
		
		reader.onload = function(e){
			
			// e.target.result holds the DataURL which
			// can be used as a source of the image:
			
			image.attr('src',e.target.result);
		};
		
		// Reading the file as a DataURL. When finished,
		// this will trigger the onload function above:
		reader.readAsDataURL(file);
		
		message.hide();
		preview.appendTo(dropbox);
		
		// Associating a preview container
		// with the file, using jQuery's $.data():
		
		$.data(file,preview);
	}

	function showMessage(msg){
		message.html(msg);
	}
  
  

});