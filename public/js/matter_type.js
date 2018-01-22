var TableDatatablesAjax = function () {

    var handleMatterType = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_matter_type"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/matter_type/list", // ajax source
                    "type": "get"
                },
                "order": [
                    [1, "asc"]
                ],// set first column as a default sort by asc,

                "bInfo": false,
                "columns": [
                        { data: "MatterTypeID" },
                        { data: "MatterTypeName" },
                        { data: "CreatedBy" },
                        { data: "UpdatedBy" },
                        { data: "CreatedOn" },
                        { data: "UpdatedOn" },
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('matter_type', data.MatterTypeID, data) ;
                            }
                        }
                ],

            }
        });
    }

    var handleMatter = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_matter"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/matter/list", // ajax source
                    "type": "get"
                },
                "order": [
                    [4, "asc"]
                ],// set first column as a default sort by asc,

                "serverSide": false,
                "pageLength": 1000,

                "bInfo": false,
                "columns": [
                        { data: "MatterID" },
                        { data: "MatterName" },
                        { data: "Tag" },
                        { data: "Description" },
                        { data: "ParentName" },
                        { data: "TypeName" },
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field                                
                                return getButtons('matter', data.MatterID, data) ;
                            }
                        }
                ],

            }
        });

        grid
    }

    var handleServiceProvider = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_service_provider"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
                console.log(response);
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/service_provider/list", // ajax source
                    "type": "get"
                },
                "order": [
                    [1, "asc"]
                ],// set first column as a default sort by asc,
                
                "serverSide": false,
                "pageLength": 1000,

                "bInfo": false,
                "columns": [
                        { data: "ServiceProviderId" },
                        { data: "ServiceProviderName" },
                        { data: "ContactEmail" },
                        { data: "ContactName" },
                        { data: "ContactPhone" },
                        //{ data: "ServiceProviderURL" },
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('service_provider', data.ServiceProviderId, data) ;
                            }
                        }
                ],

            }
        });
    }

    var handleService = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_service"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
                console.log(response);
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/service/list", // ajax source
                    "type": "get"
                },
                "order": [
                    [1, "asc"], [2, "asc"]
                ],// set first column as a default sort by asc,

                "serverSide": false,
                "pageLength": 1000,

                "bInfo": false,
                "columns": [
                        { data: "ServiceId" },
                        { data: "ServiceName" },
                        { data: "ServiceProviderName" },
                        { data: "Phone" },
                        { data: "Email" },
                        { data: "ServiceTypeName" },
                        { data: "ServiceLevelName" },
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('service', data.ServiceId, data) ;
                            }
                        }
                ],

            }
        });
    }

    var handleServiceLevel = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_service_level"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
                console.log(response);
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/service_level/list", // ajax source
                    "type": "get"
                },
                "order": [
                    [1, "asc"]
                ],// set first column as a default sort by asc,
                
                "bInfo": false,
                "columns": [
                        { data: "ServiceLevelId" },
                        { data: "ServiceLevelName" },
                        { data: "ServiceLevelDescription" },
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('service_level', data.ServiceLevelId, data) ;
                            }
                        }
                ],

            }
        });
    }

    var handleServiceType = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_service_type"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
                console.log(response);
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/service_type/list", // ajax source
                    "type": "get"
                },
                "order": [
                    [1, "asc"]
                ],// set first column as a default sort by asc,
                
                "bInfo": false,
                "columns": [
                        { data: "ServiceTypelId" },
                        { data: "ServiceTypeName" },
                        { data: "ServiceTypeDescription" },
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('service_type', data.ServiceTypelId, data) ;
                            }
                        }
                ],

            }
        });
    }

    var handleCatchment = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_catchment"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
                console.log(response);
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/catchment/list", // ajax source
                    "type": "get"
                },
                "order": [
                    [1, "asc"]
                ],// set first column as a default sort by asc,
                
                "bInfo": false,

                "columns": [
                        //{ data: "CatchmentId" },
                        { data: "PostCode" },
                        { data: "Suburb" },
                        { data: "LGC" },
                        /*
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('catchment', data.ServiceProviderId, data) ;
                            }
                        }*/
                ],

            }
        });
    }

    var handleQuestion = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_question"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded                
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/question/list", // ajax source
                    "type": "get"
                },
                "order": [
                    [1, "asc"]
                ],// set first column as a default sort by asc,
                
                "bInfo": false,
                "columns": [
                        { data: "QuestionId" },
                        { data: "QuestionName" },                        
                        { data: "QuestionCategoryName" },                        
                        { data: "QuestionTypeName" },                        
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('question', data.QuestionId, data) ;
                            }
                        }
                ],

            }
        });
    }

    var handleQuestionType = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_question_type"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded                
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/question_type/list", // ajax source
                    "type": "get"
                },
                "order": [
                    [1, "asc"]
                ],// set first column as a default sort by asc,
                
                "bInfo": false,
                "columns": [
                        { data: "QuestionTypeId" },
                        { data: "QuestionTypeName" },                        
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('question_type', data.QuestionTypeId, data) ;
                            }
                        }
                ],

            }
        });
    }

    var handleQuestionCategory = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_question_category"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded   
                confirmDialog();             
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/question_category/list", // ajax source
                    "type": "get"
                },
                "order": [
                    [1, "asc"]
                ],// set first column as a default sort by asc,
                
                "bInfo": false,
                "columns": [
                        { data: "QuestionId" },
                        { data: "QuestionName" },                        
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('question_category', data.QuestionId, data) ;
                            }
                        }
                ],

            }
        });
    }

    var handleQuestionLegalMatter = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_question_legal_matter"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded                
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/question/list_legal_matter", // ajax source
                    "type": "get"
                },
                "order": [
                    [1, "asc"]
                ],// set first column as a default sort by asc,
                
                "bInfo": false,
                "columns": [
                        { data: "QuestionId" },
                        { data: "QuestionName" },                             
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('question', data.QuestionId, data) ;
                            }
                        }
                ],

            }
        });
    }

    var handleQuestionEligibility = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_question_eligibility"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded                                
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/question/list_eligibility", // ajax source
                    "type": "get"
                },
                "order": [
                    [1, "asc"]
                ],// set first column as a default sort by asc,
                
                "bInfo": false,
                "columns": [
                        { data: "QuestionId" },
                        { data: "QuestionLabel" },                             
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('question', data.QuestionId, data) ;
                            }
                        }
                ],

            }
        });
    }
     
    var handleUser = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_user"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded                                
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/user/list", // ajax source
                    "type": "get"
                },
                "order": [
                    [2, "asc"]
                ],// set first column as a default sort by asc,
                
                "serverSide": false,
                "pageLength": 1000,
                
                "bInfo": false,
                "columns": [
                        { data: "name" },
                        { data: "email" },                             
                        { data: "role" },                             
                        { data: "sp_id" },                             
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('user', data.id, data) ;
                            }
                        }
                ],

            }
        });
    }
    
    var handleBookings = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_bookings"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded              
                
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/booking/listCalendarByUser", // ajax source
                    "type": "get"
                },
                "order": [
                    [1, "asc"]
                ],// set first column as a default sort by asc,
                
                "serverSide": false,
                "pageLength": 1000,
                
                "bInfo": false,
                "columns": [
                        { data: "BookingRef" },      
                        //{ data: "BookingDate" },  
                        { 
                        	data: null,
                        	className: "center" ,
                        	render: function (data, type, row) {
                        		// body...    
                                var booking_date = moment(data.BookingDate).toDate();
                                return moment(booking_date).format('D-M-YYYY');
                        	}
                        },  
                        { data: "BookingTime" },
                        { data: "ServiceName" },
                        { data: "FirstName" },
                        { data: "LastName" },
                        { data: "Email" },
                        { data: "Mobile" },
                        { 
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field                                
                                var sentDatesStr = '';
                                var sentDates = data.SMSSendDates;
                                                      
                                for (var i = 0, len = sentDates.length; i < len; i++) 
                                {
                                	if(sentDates[i] != '')
                                	{
                                    	sentDatesStr += moment(sentDates[i].split(' ')[0]).format('D-M-YYYY') + ', ';
                                	}
                                }

                                if( sentDatesStr == '' )
                                {
                                	sentDatesStr = '<span class="font-red">Not sent</span>';
                                } 
                                else 
                                {
                                	sentDatesStr = '<span class="font-green-jungle">' + sentDatesStr.replace(/,\s*$/, '') + '</span>';
                                }
                                //sent dates or status
                                return sentDatesStr;
                            }
                        },
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                if(data.Mobile != '')
                                {
                                    //return '<a href="#" class="btn btn-xs green remind-booking" id="'+ data.Mobile +'">Send Reminder</a>' ;
                                    var action_buttons = "";
                                    action_buttons += '<a href="/booking/delete/' + data.BookingRef  +  '" class="btn btn-danger delete-content btn-xs">Delete</a>';
                                    action_buttons += '<button class="btn btn-xs green remind-booking" onClick="sendReminderWithParams('+ data.RefNo +')">Send Reminder</button>' ;
                                    return action_buttons;
                                } else
                                {
                                    return '';
                                }
                            }
                        }
                ],

            }
        });
    }

    var handleReferrals = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_referrals"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded         
                console.log(response);
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'iB><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/referral/list", // ajax source
                    "type": "get"
                },
                "order": [
                    [0, "desc"]
                ],// set first column as a default sort by asc,
                
                "serverSide": false,
                "pageLength": 1000,
                "bInfo": false,
                buttons: [                    
                    { extend: 'print', className: 'btn blue-stripe default export-button' },
                    { extend: 'csv', className: 'btn green-meadow-stripe default export-button' }
                ],
                "scrollX": true,
                "scrollY": (screen.height <= 1200 ? screen.height - ( screen.height * 0.40) : screen.height - ( screen.height * 0.55) ) + "px",
                "scrollCollapse": true,
                "columns": [ 
                        { data: "RefNo", "orderable": true },
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {                   
                                var referred_day = moment(data.CreatedOn).toDate();
                                return moment(referred_day).format('YYYY-MM-DD');
                            }                          
                        },
                        { data: "MatterName" },
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                var location = '';
                                if( data.PostCode != '' )
                                {
                                    location = data.PostCode + ', ' + data.Suburb + ', ' + data.LGC;

                                }
                                return location;
                            }                          
                        },
                        { data: "Notes" },                             
                        { data: "ServiceProviderName" },
                        { data: "ServiceName" },                             
                        { data: "OutboundServiceProviderName" }
                        /*{
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('referral', data.BookingRef, data) ;
                            }
                        }*/
                ],

            }
        });
    }

    var handleOutboundReferrals = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_outbound_referrals"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded                         
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'iB><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/referral/listOutbound", // ajax source
                    "type": "get"
                },
                "order": [
                    [0, "desc"]
                ],// set first column as a default sort by asc,
                
                "serverSide": false,
                "pageLength": 1000,
                "bInfo": false,
                buttons: [                    
                    { extend: 'print', className: 'btn blue-stripe default export-button' },
                    { extend: 'csv', className: 'btn green-meadow-stripe default export-button' }
                ],
                "scrollX": true,
                "scrollY": (screen.height <= 1200 ? screen.height - ( screen.height * 0.40) : screen.height - ( screen.height * 0.55) ) + "px",
                "scrollCollapse": true,
                "columns": [ 
                        { data: "RefNo", "orderable": true },
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {                   
                                var referred_day = moment(data.CreatedOn).toDate();
                                return moment(referred_day).format('YYYY-MM-DD');
                            }                          
                        },                                      
                        { data: "MatterName" },
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                var location = '';
                                if( data.PostCode != '' )
                                {
                                    location = data.PostCode + ', ' + data.Suburb + ', ' + data.LGC;

                                }
                                return location;
                            }                          
                        }, 
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                if( data.Mobile == '000000000' )
                                {
                                    data.Mobile = 'N/P';
                                }
                                return data.Mobile + '  <br>' + data.Email;
                            }                          
                        },                          
                        {  "width": "300px", data: "Notes" }, 
                        { data: "ServiceProviderName" },
                        { data: "ServiceName" },
                        { data: "CreatedBy" }
                        /*{
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('referral', data.BookingRef, data) ;
                            }
                        }*/
                ],

            }
        });
    }

    var handleSmsTemplates = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_sms_templates"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded              
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/sms_template/list", // ajax source
                    "type": "get"
                },
                "order": [
                    [1, "asc"]
                ],// set first column as a default sort by asc,
                
                "serverSide": false,
                "pageLength": 1000,
                "bInfo": false,
                "columns": [
                        { data: "SerciceName" },                             
                        { data: "Template" },                             
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('sms_template', data.TemplateId, data) ;
                            }
                        }
                ],

            }
        });
    }

    var handleNreTemplates = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_nre_templates"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded              
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/no_reply_emails/listAllTemplatesBySection", // ajax source
                    "type": "get"
                },
                "order": [
                    [0, "asc"]
                ],// set first column as a default sort by asc,
                
                "serverSide": false,
                "pageLength": 1000,
                "bInfo": false,
                "columns": [
                        { data: "Name", "orderable": true },                             
                        { data: "Section" },                             
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('no_reply_emails/templates', data.RefNo, data) ;
                            }
                        }
                ],

            }
        });
    }

    var handleNreLog = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax_nre_log"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded              
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
                confirmDialog();
            },

            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
               
                "dom": "<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'>>",

                "ajax": {
                    "url": "/no_reply_emails/listAllLogRecords", // ajax source
                    "type": "get"
                },
                "order": [
                    [3, "desc"]
                ],// set first column as a default sort by asc,
                
                "serverSide": false,
                "pageLength": 1000,
                "bInfo": false,
                "columns": [
                        { data: "ToAddress", "orderable": true },                             
                        { data: "Subject" },
                        { data: "Section" }, 
                        { 
                            data: null,
                            type: 'date-uk',
                            className: "center" ,
                            render: function (data, type, row) {
                                // body...    
                                var sent_on_date = moment(data.SentOn).toDate();
                                return moment(sent_on_date).format('DD/MM/YYYY HH:mm:ss');
                            }
                        }
                ],

            }
        });
    }
     
    var getButtons = function (controller, id, data) {

        var edit_btn = '';
        var delete_btn = '';
        
        var role = $(".role").attr("id");
        var sp_id = $(".sp_id").attr("id");

        if( role == "VLA" || role == "CLC") {
            //var edit_btn = '<a href="/' + controller + '/show/' + id  +  '" class="btn blue edit-content btn-xs">View</a>';
        } else {

            edit_btn = '<a href="/' + controller + '/show/' + id  +  '" class="btn btn-warning edit-content btn-xs">Edit</a>';
            switch(controller) 
            {
                case 'service':
                    if( data.ServiceProviderId == sp_id || sp_id == 0 )
                    {
                        edit_btn = '<a href="/' + controller + '/show/' + id  +  '" class="btn btn-warning edit-content btn-xs">Edit</a>';
                        delete_btn = '<a href="/' + controller + '/delete/' + id  +  '" class="btn btn-danger delete-content btn-xs">Delete</a>';
                    } else {
                        edit_btn = '<a href="/' + controller + '/show/' + id  +  '" class="btn blue edit-content btn-xs">View</a>';
                    }
                    break;
                case 'service_provider':
                    if( data.ServiceProviderId == sp_id || sp_id == 0 )
                    {
                        edit_btn = '<a href="/' + controller + '/show/' + id  +  '" class="btn btn-warning edit-content btn-xs">Edit</a>';
                        if( sp_id == 0 )
                        {
                            delete_btn = '<a href="/' + controller + '/delete/' + id  +  '" class="btn btn-danger delete-content btn-xs">Delete</a>';
                        }
                    }
                    break;
                case 'question':
                case 'matter':
                case 'user':
                case 'sms_template':                    
                case 'no_reply_emails/templates':                    
                    delete_btn = '<a href="/' + controller + '/delete/' + id  +  '" class="btn btn-danger delete-content btn-xs">Delete</a>';
                    break;
                default:                    
            }

        }

        return edit_btn + delete_btn;   	

    }

    var initSearchBox = function (){

        var table = $('table[id^="datatable_ajax_"]').DataTable();
        $('#search_box').on('keyup keypress change', function () {
            table.search( this.value ).draw();
        });
    }

    //Support to UK dates and sorting https://datatables.net/plug-ins/sorting/date-uk
    var date_uk = function()
    {
        jQuery.extend( jQuery.fn.dataTableExt.oSort, {
            "date-uk-pre": function ( a ) {
                if (a == null || a == "") {
                    return 0;
                }
                var ukDatea = a.split('/');
                return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
            },
         
            "date-uk-asc": function ( a, b ) {
                return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            },
         
            "date-uk-desc": function ( a, b ) {
                return ((a < b) ? 1 : ((a > b) ? -1 : 0));
            }
        } );
    };

    return {
        //main function to initiate the module
        init: function () {
            date_uk();
            handleMatter();
            handleMatterType();
            handleServiceProvider();
            handleService();
            handleServiceLevel();
            handleServiceType();
            handleCatchment();
            handleQuestion();
            handleQuestionType();
            handleQuestionCategory();
            handleQuestionLegalMatter();
            handleQuestionEligibility();
            handleUser();
            handleBookings();
            handleReferrals();
            handleOutboundReferrals();
            handleSmsTemplates();
            handleNreTemplates();
            handleNreLog();
            initSearchBox();
        }

    };

}();


var confirmDialog = function() 
{
    $( ".delete-content" ).on( "click", function(e) 
    {        
        var r = confirm("Are you sure that you want to delete it?\n To confirm press OK or Cancel.");
        if (r == true) 
        {
            //Continue to the event
        } else {
            e.preventDefault();       
        }
    });  
}

jQuery(document).ready(function() {
    TableDatatablesAjax.init();
});