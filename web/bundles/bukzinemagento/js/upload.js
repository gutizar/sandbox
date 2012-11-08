 $(function() {
	var uploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight',
		browse_button : 'pickfiles',
		container : 'container',
		max_file_size : '10mb',
		url : 'handle',
		flash_swf_url : '/plupload/js/plupload.flash.swf',
		silverlight_xap_url : '/plupload/js/plupload.silverlight.xap',
		filters : [
			{title : "Image files", extensions : "jpg,gif,png"},
			{title : "Zip files", extensions : "zip"},
			{title: "PDF Dokumenti", extensions: "pdf"}
		],
		resize : {width : 320, height : 240, quality : 90}
	});

	uploader.bind('Init', function(up, params) {
		if (params.runtime != null)
			$('#filelist').html("<div>Current runtime: " + params.runtime + "</div>");	
		else
			$('#filelist').html("<div>No runtime available, please contact support!</div>")
	});

	$('#uploadfiles').click(function(e) {
		uploader.start();
		e.preventDefault();
	});

	uploader.init();

	uploader.bind('FilesAdded', function(up, files) {
		$.each(files, function(i, file) {
			$('#filelist').append(
				'<div id="' + file.id + '">' +
				file.name + ' (' + plupload.formatSize(file.size) + 
				') <b></b><div class="progress progress-striped active"><div class="bar" style="width: 0%;"></div></div>' +
				'</div>');
		});

		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('UploadProgress', function(up, file) {
		$('#' + file.id + " b").html(file.percent + "%");
		$('#' + file.id + " div.bar").width(file.percent + "%");
	});

	uploader.bind('Error', function(up, err) {
		$('#filelist').append("<div>Error: " + err.code +
			", Message: " + err.message +
			(err.file ? ", File: " + err.file.name : "") +
			"</div>"
		);

		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('FileUploaded', function(up, file, response) {
		$('#' + file.id + " b").html("100%");
		$('#pickfiles').attr('disabled', 'disabled');
		$('#uploadfiles').attr('disabled', 'disabled');
		var $response = jQuery.parseJSON(response.response);
		if ($('a#continue').length == 0)
		{
			var $newLink = ' <a id="continue" class="btn" href="' + $response.url + '">Continue</a>';
			$('#container button:last').after($newLink);	
		}
	});
});		