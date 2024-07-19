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
						{
							pattern: '<%= pkg.last_version %>',
							replacement: '<%= pkg.version %>',
						},
					],
				},
			},
		},
	});

	// Load grunt plugins
	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-string-replace');

	// Register tasks
	grunt.registerTask('default', ['string-replace', 'compress']);
};
