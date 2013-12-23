<?php include 'header.php' ?>

<div class="container">

	<div class="page-header">
		<h1><?= $this->pageTitle ?></h1>
		<p class="lead">Enter a Twitter handle in the box below and click on Submit to view the 10 most frequent words that user has used in their tweets.</p>
	</div>
	
	<div class="col-md-6 col-md-offset-3">

		<div class="alert alert-danger error"></div>

		<form role="form" method="get">
			<div class="form-group input-group">
				<span class="input-group-addon">@</span>
				<input type="text" class="form-control" placeholder="Username" autocomplete="off" name="username">
			</div>
			<div class="form-group">
			    <input type="text" class="form-control" name="tweetcount" placeholder="No. of tweets (default 20)">
			</div>
			<button type="submit" class="btn btn-default">Submit</button>
		</form>

		<hr />
		<div class="progress progress-striped active loader">
		  <div class="progress-bar"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
		    <span class="sr-only">45% Complete</span>
		  </div>
		</div>
		<div class="results">
			<h4>Top keywords of @<span></span><em class="small">2.56 seconds</em></h4>
			<table class="table table-striped table-condensed table-hover">
				<thead>
					<tr>
						<th>Keyword</th>
						<th>Count</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>

</div>
<script type="text/javascript">
$(function(){
	var url = "http://localhost/thinkcube/public/twitter/freqwords/";
	var $reswrap = $('div.results');
	var $restable = $('table tbody', $reswrap);

	$('form').submit(function(e){
		var username = $("input[name='username']").val() || ' ';
		var limit = $("input[name='tweetcount']").val();

		hideError();
		showLoader();
		$reswrap.hide(0);
		
		$.ajax({
			url: url + username + '/' + limit,
			dataType: 'json',
			success: function(data){

				if(data.success === false){
					showError(data.error);
					hideLoader();	
					return false;
				}

				$restable.empty();

				$.each(data.result.top10, function(index, val){

					var $resrow = $('<tr></tr>');
					$resrow.append('<td>'+index+'</td>');
					$resrow.append('<td>'+val+'</td>');

					$resrow.appendTo($restable);

					$('h4 span', $reswrap).text(username);

				});

				$('h4 em', $reswrap).text(data.exec_time + ' seconds');				

				hideLoader();
				$reswrap.show(0);

			}
		});

		e.preventDefault();
		return false;

		function showLoader(){
			$('.loader').show(0);
		}

		function hideLoader(){
			$('.loader').hide(0);
		}

		function showError(msg){
			$('.alert.error').html(msg).show(0);
		}

		function hideError(){
			$('.alert.error').hide(0);
		}
	});

	$("input[name='username']").focus();
});
</script>
<?php include 'footer.php' ?>