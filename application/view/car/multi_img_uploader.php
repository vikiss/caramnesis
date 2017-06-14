<script src="/js/plupload/js/plupload.full.min.js"></script>

<script type="text/javascript">

function postAjax(url, data, success) {
	    var params = typeof data == 'string' ? data : Object.keys(data).map(
	            function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) }
	        ).join('&');
	
	    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	    xhr.open('POST', url);
	    xhr.onreadystatechange = function() {
	        if (xhr.readyState>3 && xhr.status==200) { success(xhr.responseText); }
	    };
	    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	    xhr.send(params);
	    return xhr;
	}

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
    },
   
    
    
		FilesAdded: function(up, files) {
				plupload.each(files, function(file) {
          if (file.type.indexOf("image") > -1) 
              {
					var img = new o.Image();
                    img.onload = function() {
                        // create a thumb placeholder
                        var li = document.createElement('li');
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
    //console.log(object.response);
    var t = JSON.parse(object.response);
    var val = document.getElementById('user_images').value;
    val = (val ? val + ',' : '') + t.name;
   //console.log(val);   
    document.getElementById('user_images').value=val;
//save imagelist to database  
postAjax('/car/write_attr', {"key":'attritem-1', "value":val, "car_id":'<?= $car_id; ?>', "chapter":'<?= $entry->chapter; ?>', "entry":'PICTURES', "unit":'', "validate":'text'  }, function(data){ console.log(data); });

    //save imagelist to database
    document.getElementById(file.id).getElementsByClassName('progressHolder')[0].getElementsByClassName('progress')[0].style.backgroundColor  = '#2ECC40';
    
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