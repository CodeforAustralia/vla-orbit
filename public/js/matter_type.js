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
                                return getButtons('matter_type', data.MatterTypeID) ;
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
                    [1, "asc"]
                ],// set first column as a default sort by asc,

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
                                return getButtons('matter', data.MatterID) ;
                            }
                        }
                ],

            }
        });
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
                
                "bInfo": false,
                "columns": [
                        { data: "ServiceProviderId" },
                        { data: "ServiceProviderName" },
                        { data: "ContactEmail" },
                        { data: "ContactName" },
                        { data: "ContactPhone" },
                        { data: "ServiceProviderURL" },
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('service_provider', data.ServiceProviderId) ;
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
                    [1, "asc"]
                ],// set first column as a default sort by asc,
                
                "bInfo": false,
                "columns": [
                        { data: "ServiceId" },
                        { data: "ServiceName" },
                        { data: "Description" },
                        { data: "Phone" },
                        { data: "Email" },
                        { data: "Wait" },
                        { data: "ServiceTypeName" },
                        { data: "ServiceLevelName" },
                        {
                            data: null,
                            className: "center",
                            render: function ( data, type, row ) {
                                // Combine the first and last names into a single table field
                                return getButtons('service', data.ServiceId) ;
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
                                return getButtons('service_level', data.ServiceLevelId) ;
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
                                return getButtons('service_type', data.ServiceTypelId) ;
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
                                return getButtons('catchment', data.ServiceProviderId) ;
                            }
                        }*/
                ],

            }
        });
    }

    var getButtons = function (controller, id) {

        var delete_btn = '<a href="/' + controller + '/delete/' + id  +  '" class="badge badge-danger">Delete</a>';
        var edit_btn = '';
        if (controller === 'service_provider' || controller === 'service') 
        {
            var edit_btn = '<a href="/' + controller + '/show/' + id  +  '" class="badge badge-warning">Edit</a>';
        }
        return edit_btn + delete_btn;   	

    }

    return {
        //main function to initiate the module
        init: function () {
            handleMatter();
            handleMatterType();
            handleServiceProvider();
            handleService();
            handleServiceLevel();
            handleServiceType();
            handleCatchment();
        }

    };

}();

jQuery(document).ready(function() {
    TableDatatablesAjax.init();
});