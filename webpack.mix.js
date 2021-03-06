let mix = require('laravel-mix');

mix.styles([
	'node_modules/font-awesome/font-awesome.min.css',
	'public/theme/css/dashboard.css',
	'public/theme/plugins/charts-c3/plugin.css',
	'public/theme/plugins/maps-google/plugin.css',
], 'public/css/vendor.css');

mix.scripts([
	'public/theme/js/vendors/jquery-3.2.1.min.js',
	'public/theme/js/vendors/bootstrap.bundle.min.js',
	'public/theme/js/vendors/jquery.sparkline.min.js',
	'public/theme/js/vendors/selectize.min.js',
	'public/theme/js/core.js'
], 'public/js/vendor.js');

mix.copy([
	'node_modules/font-awesome/fonts/*'
], 'public/fonts');

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.scripts([
	'public/datatable/js/jquery.dataTables.min.js',
	'public/datatable/js/dataTables.bootstrap4.min.js',
	'public/datatable/buttons/js/dataTables.buttons.min.js',
	'public/datatable/buttons/js/buttons.bootstrap4.min.js',
	'public/datatable/js/jszip.min.js',
	'public/datatable/js/pdfmake.min.js',
	'public/datatable/js/vfs_fonts.js',
	'public/datatable/buttons/js/buttons.html5.min.js'
], 'public/js/datatable.js');   

mix.styles([
	'public/datatable/css/dataTables.bootstrap4.min.css',
	'public/datatable/buttons/css/buttons.bootstrap4.min'
], 'public/css/datatable.css');
