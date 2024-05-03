module.exports = function (grunt) {
	// Project config
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		compress: {
			dist: {
				options: {
					archive: './dist/<%= pkg.name %>.zip',
					mode: 'zip',
				},
				files: [
					{ src: './admin/css/**', dest: '<%= pkg.name %>/' },
					{ src: './admin/images/**', dest: '<%= pkg.name %>/' },
					{ src: './admin/js/**', dest: '<%= pkg.name %>/' },
					{ src: './admin/partials/**', dest: '<%= pkg.name %>/' },
					{ src: './admin/class-infinite-admin.php', dest: '<%= pkg.name %>/' },
					{ src: './admin/index.php', dest: '<%= pkg.name %>/' },
					{ src: './icons/**', dest: '<%= pkg.name %>/' },
					{ src: './includes/**', dest: '<%= pkg.name %>/' },
					{ src: './languages/**', dest: '<%= pkg.name %>/' },
					{ src: './plugin-update-checker/**', dest: '<%= pkg.name %>/' },
					{ src: './public/css/**', dest: '<%= pkg.name %>/' },
					{ src: './public/images/**', dest: '<%= pkg.name %>/' },
					{ src: './public/js/**', dest: '<%= pkg.name %>/' },
					{ src: './public/partials/**', dest: '<%= pkg.name %>/' },
					{ src: './public/class-infinite-public.php', dest: '<%= pkg.name %>/' },
					{ src: './public/index.php', dest: '<%= pkg.name %>/' },
					{ src: './README.md', dest: '<%= pkg.name %>/' },
					{ src: './index.php', dest: '<%= pkg.name %>/' },
					{ src: './infinite-plugin.php', dest: '<%= pkg.name %>/' },
					{ src: './uninstall.php', dest: '<%= pkg.name %>/' },
					{ src: './LICENSE.txt', dest: '<%= pkg.name %>/' },
				],
			},
		},
		'string-replace': {
			dist: {
				files: { './': ['<%= pkg.name %>.php'] },
				options: {
					replacements: [
						{
							pattern: '<%= pkg.last_version %>',
							replacement: '<%= pkg.version %>',
						},
					],
				},
			},
		},
	});

	// grunt.registerTask('manifest', function (key, value) {
	// 	// Get config package.json
	// 	var pkg = grunt.config.get('pkg');

	// 	// Set changing props & default props
	// 	var website = 'https://github.com/R3BLcreative';
	// 	var rootPath = website + '/plugin-infinite/releases/latest/';

	// 	// Setup WP info
	// 	var date = new Date();
	// 	var wp = {
	// 		name: pkg['title'],
	// 		slug: pkg['name'],
	// 		version: pkg['version'],
	// 		added: pkg['created'],
	// 		last_updated:
	// 			date.getFullYear() +
	// 			'-' +
	// 			('0' + (date.getMonth() + 1)).slice(-2) +
	// 			'-' +
	// 			('0' + date.getDate()).slice(-2) +
	// 			' ' +
	// 			date.getHours() +
	// 			':' +
	// 			date.getMinutes() +
	// 			':00',
	// 		requires: '6.4.3',
	// 		tested: '6.4.3',
	// 		requires_php: '8.0.0',
	// 		download_url: rootPath + pkg['name'] + '.zip?v=' + pkg['version'],
	// 		author: "<a href'" + website + "' target='_blank'>James Cook</a>",
	// 		author_profile: website,
	// 		donate_link: website,
	// 		homepage: website,
	// 		sections: {
	// 			description: pkg['description'],
	// 		},
	// 		banners: {
	// 			low: rootPath + 'banner-772x250.jpg',
	// 			high: rootPath + 'banner-1544x500.jpg',
	// 		},
	// 	};

	// 	// Path to write/update file
	// 	var infoJsonFile = './dist/info.json';

	// 	// Write/update file
	// 	grunt.file.write(infoJsonFile, JSON.stringify(wp));
	// });

	// Load grunt plugins
	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-string-replace');

	// Register tasks
	// grunt.registerTask('default', ['string-replace', 'compress', 'manifest']);
	grunt.registerTask('default', ['string-replace', 'compress']);
};
