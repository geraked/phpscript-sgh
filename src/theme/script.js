$(document).ready(function(){

	hideLoading();

	$.fn.initializeMyPlugin = function () {

		// DatePicker
		//window.prettyPrint && prettyPrint();
		$('#ambirth_date').datepicker({
			language: 'fa',
			startDate: '1300/01/01',
			endDate: '1420/01/01',
			clearBtn: true,
		});	
		$('#alcreate_date, #amjoin_date, #atcreate_date input').datepicker({
			language: 'fa',
			startDate: '1380/01/01',
			endDate: '1420/01/01',
			todayBtn: "linked",
			clearBtn: true,
		});
		$('.input-daterange').datepicker({
			startDate: '1380/01/01',
			endDate: '1420/01/01',        
			todayBtn: "linked",
			clearBtn: true,
			language: "fa"
		});	
		
		$("#example1").dataTable({
			"dom":			'<"row top"<"col-sm-6"f><"col-sm-6"l>>rt<"row bottom"<"col-sm-6"i><"col-sm-6"p>>',
			"ordering": true,
			"responsive": true,
			"language": {
				"lengthMenu":		"تعداد قابل نمایش در هر صفحه: _MENU_",
				"search":			"جستجو: ",
				"zeroRecords": 		"موردی یافت نشد",
				//"info": 			"نمایش صفحه _PAGE_ از _PAGES_ صفحه",
				"info": 			"نمایش صفحه _PAGE_ از _PAGES_ صفحه",
				"infoFiltered":   	"",
				"infoEmpty": 		"موردی وجود ندارد",
				"loadingRecords":	"در حال بارگذاری...",
				"paginate": {
					"first":      "نخست",
					"last":       "آخر",
					"next":       "بعدی",
					"previous":   "قبلی"
				}				
			}
		});
		
		// Initialize Select2 Elements
		$(".select2").select2({
			language: "fa",
			dir: "rtl"
		});
		
		
		
		// ---------------------- Admin Add Transactions Page ----------------------
		if( $('#atmember_id select[name=member_id]').val()!=""){
			$("#attype").show(500);
			//$("#atmember_id select[name=member_id]").attr('disabled','disabled');
		}
		else{
			$("#attype").hide(500)
		}	
		$('#atmember_id select[name=member_id]').on('change',function(){
			var member_idVal = $(this).val();
			var typeVal = $('#attype select[name=type]').val();
			$.ajax({
				type: 'POST',
				url: '../admin/transactions-ajax.php?op=attype',
				data: { member_id: member_idVal, },
				success: function(data) {
					$('#attype select[name=type]').html(data);
					$("#attype select[name=type] option").each(function(){
						$(this).siblings("[value='"+ this.value+"']").remove();
					});				
				}
			});
			$.ajax({
				type: 'POST',
				url: '../admin/transactions-ajax.php?op=atloan_id',
				data: { member_id: member_idVal, },
				success: function(data) {
					$('#atloan_id select[name=loan_id]').html(data);
				}
			});
			if( $(this).val()!=""){
				$("#attype").show(500);
				//$("#atmember_id select[name=member_id]").attr('disabled','disabled');
			}
			else{
				$("#attype").hide(500)
			}
		});
		
		
		if( $('#attype select[name=type]').val()!=""){
			if( $('#attype select[name=type]').val()=="پرداخت قسط" || $('#attype select[name=type]').val()=="دریافت وام" ) {
				$("#atloan_id").show(500);
			}
			else {
				$("#atloan_id").hide(500)
			}
			$("#atamount, #atcreate_date, #atdescription, #atsubmit").show(500);
			//$("#attype select[name=type]").attr('disabled','disabled');
		}
		else{
			$("#atloan_id, #atamount, #atcreate_date, #atdescription, #atsubmit").hide(500)
		}	
		$('#attype select[name=type]').on('change',function(){
			if( $(this).val()!=""){
				if( $(this).val()=="پرداخت قسط" || $(this).val()=="دریافت وام" ) {
					$("#atloan_id").show(500);
				}
				else {
					$("#atloan_id").hide(500)
				}
				$("#atamount, #atcreate_date, #atdescription, #atsubmit").show(500);
				//$("#attype select[name=type]").attr('disabled','disabled');
			}
			else{
				$("#atloan_id, #atamount, #atcreate_date, #atdescription, #atsubmit").hide(500)
			}
		});
		

		$('.pdf-link').click(function (e) {
			e.preventDefault();
			window.open($(this).attr('href'), "_blank");
		});	

		$('.img-del-btn').click(function (e) {
			$('<input type="hidden" name="delpic" value="1">').insertBefore($(this));
			$(this).closest('form').submit();
		});			
		
	};

	// Pjax
	$.pjax.defaults.timeout = 10000;
	$.pjax.defaults.fragment = '#pjax-container';
	$.pjax.defaults.push = false;
	$.pjax.defaults.replace = true;
	$.pjax.defaults.maxCacheLength = 0;
	
	$(document).pjax('[data-pjax] a, a[data-pjax]', '#pjax-container');
	$(document).on('ready pjax:end', function(event) {
		$(event.target).initializeMyPlugin();
	});
	$(document).on('pjax:beforeSend', function() {
		showLoading();
	});
	$(document).on('pjax:complete', function() {
		hideLoading();
	});	
	$(document).on('submit', 'form[data-pjax]', function(event) {
		$.pjax.submit(event, '#pjax-container');
	});

});


// Add comma to money input
function addComma(Num) {
	Num += '';
	Num = Num.replace(',', ''); Num = Num.replace(',', ''); Num = Num.replace(',', '');
	Num = Num.replace(',', ''); Num = Num.replace(',', ''); Num = Num.replace(',', '');
	x = Num.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1))
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	return x1 + x2;
}


function showLoading() {
	$('.modal').modal('hide'); // closes all active pop ups.
	$('.modal-backdrop').remove(); // removes the grey overlay.
	$('.content-wrapper').css('position', 'relative');
	disableScroll();
	$('#loading').show();	
}

function hideLoadingUtil() {
	$('.content-wrapper').css('position', 'unset');
	enableScroll();	
}

function hideLoading() {
	$('#loading').fadeOut(300);
	setTimeout(hideLoadingUtil, 300);
}


// left: 37, up: 38, right: 39, down: 40,
// spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
var keys = {37: 1, 38: 1, 39: 1, 40: 1};
function preventDefault(e) {
  e.preventDefault();
}
function preventDefaultForScrollKeys(e) {
  if (keys[e.keyCode]) {
    preventDefault(e);
    return false;
  }
}
// modern Chrome requires { passive: false } when adding event
var supportsPassive = false;
try {
  window.addEventListener("test", null, Object.defineProperty({}, 'passive', {
    get: function () { supportsPassive = true; } 
  }));
} catch(e) {}
var wheelOpt = supportsPassive ? { passive: false } : false;
var wheelEvent = 'onwheel' in document.createElement('div') ? 'wheel' : 'mousewheel';
// call this to Disable
function disableScroll() {
  window.addEventListener('DOMMouseScroll', preventDefault, false); // older FF
  window.addEventListener(wheelEvent, preventDefault, wheelOpt); // modern desktop
  window.addEventListener('touchmove', preventDefault, wheelOpt); // mobile
  window.addEventListener('keydown', preventDefaultForScrollKeys, false);
}
// call this to Enable
function enableScroll() {
  window.removeEventListener('DOMMouseScroll', preventDefault, false);
  window.removeEventListener(wheelEvent, preventDefault, wheelOpt); 
  window.removeEventListener('touchmove', preventDefault, wheelOpt);
  window.removeEventListener('keydown', preventDefaultForScrollKeys, false);
}