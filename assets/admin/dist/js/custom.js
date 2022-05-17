$(document).ready(function () {

    /** Packages list datatable */

    $('#packages').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
        
    $("#packagefrm").validate({
        rules: {
            packagename: {
                required: true,
            },
            packageamount: {
                required: true,
                number: true
            },
            packageduration: {
                required: true,
            },
         
        },
        // Specify the validation error messages
        messages: {
            packagename: {
                required: "Package Name is required.",
            },
            packageamount: {
                required: "Package amount is required.",
                number:"Please enter numbers Only"
            },
            packageduration: {
                required: "Select Duration.",
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /** Payment Method  */

    $("#paymentmethodfrm").validate({
        rules: {
            name: {
                required: true,
            },
        
        },
        // Specify the validation error messages
        messages: {
            name: {
                required: "Payment method name is required.",
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $('#confirm-delete').on('show.bs.modal', function (e) {
        $(this).find('.danger').attr('href', $(e.relatedTarget).data('href'));
    });

    /** Category */

    $('#category').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });

    $("#categoryfrm").validate({
        rules: {
            categoryname: {
                required: true,
            },
        
        },
        // Specify the validation error messages
        messages: {
            categoryname: {
                required: "Category name is required.",
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /** Payment Method */

    $('#paymentmethod').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
  
    /** Pages  */

    $('#pages').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });

     /** Pages  */

     $("#pagesfrm").validate({
        rules: {
            title: {
                required: true,
            },
            description: {
                required: true,
            },
        
        },
        // Specify the validation error messages
        messages: {
            title: {
                required: "Title is required.",
            },
            description: {
                required: "Description is required.",
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

	


    /** Settings  */

    $("#settingfrm").validate({
        rules: {
            value: {
                required: true,
            },
       
        },
        // Specify the validation error messages
        messages: {
            value: {
                required: "Value is required.",
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });


    /** Login History */

    $('#login_history').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });

    /** Users List */

    $('#users').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
	
	/** User's Events List */

    $('#userevents').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
	
	/** User's Media List */

    $('#usermedia').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });

    /** Songs List */

    $('#songs').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });

	/** Currency List */

    $('#currency').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
	
	$("#currencyfrm").validate({
        rules: {
            name: {
                required: true,
            },
            code: {
                required: true,
            },
			symbol: {
                required: true,
            },
       
        },
        // Specify the validation error messages
        messages: {
            name: {
                required: "Name is required.",
            },
            code: {
                required: "Code is required.",
            },
			symbol: {
                required: "Symbol is required.",
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
	
	/* User Friends */
	
		
	 $('#userfriends').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
	
	/* User Advertisement */
	
	$('#useradvertisement').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
	
	/* Leaderboard */
	
	 $('#leaderboard').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
	
	/* Points */
	
	 $('#pointstbl').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
	
	$("#pointfrm").validate({
        rules: {
            description: {
                required: true,
            },
            points: {
                required: true,
                number: true
            }
         
        },
        // Specify the validation error messages
        messages: {
            description: {
                required: "Description is required.",
            },
            points: {
                required: "Points are required.",
                number:"Please enter numbers Only"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("#songsfrm").validate({
        rules: {
            title: {
                required: true,
            },
            songupload: {
                required: true,
            },
       
        },
        // Specify the validation error messages
        messages: {
            title: {
                required: "Title is required.",
            },
            songupload: {
                required: "Upload Song is required.",
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

     /** Settings List */

     $('#settings').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
   
});