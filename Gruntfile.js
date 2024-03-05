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
					{ src: './admin/css/**', dest: 'infinite-plugin/' },
					{ src: './admin/images/**', dest: 'infinite-plugin/' },
					{ src: './admin/js/**', dest: 'infinite-plugin/' },
					{ src: './admin/partials/**', dest: 'infinite-plugin/' },
					{ src: './admin/class-infinite-admin.php', dest: 'infinite-plugin/' },
					{ src: './admin/index.php', dest: 'infinite-plugin/' },
					{ src: './icons/**', dest: 'infinite-plugin/' },
					{ src: './includes/**', dest: 'infinite-plugin/' },
					{ src: './languages/**', dest: 'infinite-plugin/' },
					{ src: './public/css/**', dest: 'infinite-plugin/' },
					{ src: './public/images/**', dest: 'infinite-plugin/' },
					{ src: './public/js/**', dest: 'infinite-plugin/' },
					{ src: './public/partials/**', dest: 'infinite-plugin/' },
					{ src: './public/class-infinite-public.php', dest: 'infinite-plugin/' },
					{ src: './public/index.php', dest: 'infinite-plugin/' },
					{ src: './README.md', dest: 'infinite-plugin/' },
					{ src: './index.php', dest: 'infinite-plugin/' },
					{ src: './infinite-plugin.php', dest: 'infinite-plugin/' },
					{ src: './uninstall.php', dest: 'infinite-plugin/' },
					{ src: './LICENSE.txt', dest: 'infinite-plugin/' },
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
						{
							pattern: "'<%= pkg.last_version %>'",
							replacement: "'<%= pkg.version %>'",
						},
					],
				},
			},
		},
	});

	grunt.registerTask('manifest', function (key, value) {
		// Get config package.json
		var pkg = grunt.config.get('pkg');
		var wp = pkg['wordpress'];

		// Set changing props & default props
		var website = 'https://r3blcreative.com';
		var rootPath = website + '/r3bl-updates/plugins/' + pkg['name'] + '/';

		wp['name'] = pkg['title'];
		wp['slug'] = pkg['name'];
		wp['added'] = pkg['created'];
		wp['version'] = pkg['version'];
		wp['requires'] = '6.4.3';
		wp['tested'] = '6.4.3';
		wp['requires_php'] = '8.0.0';
		wp['download_url'] = rootPath + pkg['name'] + '.zip?v=' + pkg['version'];
		wp['author'] = "<a href='" + website + "' target='_blank'>James Cook</a>";
		wp['author_profile'] = website;
		wp['donate_link'] = website;
		wp['homepage'] = website;

		wp['sections']['description'] = pkg['description'];

		wp['banners']['low'] = rootPath + 'banner-772x250.jpg';
		wp['banners']['high'] = rootPath + 'banner-1544x500.jpg';

		// Set to current date time
		var date = new Date();
		wp['last_updated'] =
			date.getFullYear() +
			'-' +
			('0' + (date.getMonth() + 1)).slice(-2) +
			'-' +
			('0' + date.getDate()).slice(-2) +
			' ' +
			date.getHours() +
			':' +
			date.getMinutes() +
			':00';

		// Path to write/update file
		var infoJsonFile = './dist/info.json';

		// Write/update file
		grunt.file.write(infoJsonFile, JSON.stringify(wp));
	});

	// Load grunt plugins
	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-string-replace');

	// Register tasks
	grunt.registerTask('default', ['string-replace', 'compress', 'manifest']);
};
