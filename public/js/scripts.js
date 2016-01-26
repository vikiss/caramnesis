function uploadFile()
              {
              var file = _("image1").files[0];
              formdata.append("file1", file);
              var ajax = new XMLHttpRequest();
             ajax.upload.addEventListener("progress", myProgressHandler, false);
              ajax.addEventListener("load", myCompleteHandler, false);
              ajax.addEventListener("error", myErrorHandler, false);
              ajax.addEventListener("abort", myAbortHandler, false);
              ajax.open("POST", "file_upload_parser.php"); ajax.send(formdata);
              }
              
function myProgressHandler(event)
         {
           _("loaded_n_total").innerHTML =
                     "Uploaded "+event.loaded+" bytes of "+event.total;
                      var percent = (event.loaded / event.total) * 100;
           _("progressBar").value = Math.round(percent);
           _("status").innerHTML = Math.round(percent)+"% uploaded...
                                                        please wait";
         }
function myCompleteHandler(event)
         {
           _("status").innerHTML = event.target.responseText;
           _("progressBar").value = 0;
          }
function myErrorHandler(event)
          {
           _("status").innerHTML = "Upload Failed";
          }
function myAbortHandler(event)
          {
          _("status").innerHTML = "Upload Aborted";
          }              
