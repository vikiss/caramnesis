<script src="/js/plupload/js/plupload.full.min.js"></script>

<script type="text/javascript">

var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'fileinput', // you can pass an id...
	container: document.getElementById('container'), // ... or DOM Element itself
  drop_element: document.getElementById('dropbox'),
	url : '/car/pic_upl?car_id=<?= $car_id; ?>',
	flash_swf_url : '../js/Moxie.swf',
	silverlight_xap_url : '../js/Moxie.xap',
  
	
  resize: {
    width: 1600,
    height: 1600
  },
  
	filters : {
		max_file_size : '24mb',
		mime_types: [
			{title : "Image files", extensions : "jpg,gif,png"},
      { title : "PDF files", extensions : "pdf" }
		]
	},

	init: {
		PostInit: function() {
			document.getElementById('filelist').innerHTML = '';

			/*document.getElementById('start-upload').onclick = function() {
				uploader.start();
				return false;
			};*/
		},

		/*FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
        uploader.start();
			});
		}, */
    
    
    
    
    			FilesAdded: function(up, files) {
                    
				plupload.each(files, function(file) {

        if (file.type.indexOf("image") > -1) 
        
              {
					var img = new o.Image();
                    
                    img.onload = function() {
                        // create a thumb placeholder
                        var li = document.createElement('li');
                        document.getElementById("start-upload").style.visibility = "hidden";
                        li.id = file.id;
                        document.getElementById('filelist').appendChild(li);
                        
                        // embed the actual thumbnail
                        this.embed(li.id, {
                            width: 120,
                            height: 120,
                            crop: false
                        });
                        
                        document.getElementById(li.id).innerHTML += '<div class="progressHolder"><div class="progress"></div></div>'; 
                    };
                    
                    img.load(file.getSource()); 
                    
              } else {
              
                var li = document.createElement('li');
                        li.id = file.id;
                        document.getElementById('filelist').appendChild(li);
                    document.getElementById(li.id).innerHTML += '<div>' + file.name + ' (' + plupload.formatSize(file.size) + ') </div>';
                    document.getElementById(li.id).innerHTML += '<div class="progressHolder"><div class="progress"></div></div>';
                    
                    
                    }
                    
                    
                  uploader.start();
				});
			},
    
    
    
    
    
    
    
    
    
   FileUploaded: function(upldr, file, object) {
    console.log(object.response);
    var t = JSON.parse(object.response);
    var val = document.getElementById('user_images').value;
    val = (val ? val + ',' : '') + t.name;
   //console.log(val);   
    document.getElementById('user_images').value=val;
    document.getElementById(file.id).getElementsByClassName('progressHolder')[0].getElementsByClassName('progress')[0].style.backgroundColor  = '#2ECC40';
    document.getElementById("start-upload").style.visibility = "visible";
},

		UploadProgress: function(up, file) {
			document.getElementById(file.id).getElementsByClassName('progressHolder')[0].getElementsByClassName('progress')[0].style.width = file.percent.toString() + '%'; 
		},

		Error: function(up, err) {
			document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
		},
    
    UploadComplete: function() {
      if(document.getElementById('fileinput').value){
        try{
            document.getElementById('fileinput').value = ''; //for IE11, latest Chrome/Firefox/Opera...
        } catch(err){ document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message)); }
      }

      }
    
    
	}
});

uploader.init();

</script>