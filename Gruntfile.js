module.exports = function (grunt) {
	// Project config
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		compress: {
			dist: {
				options: {
					archive: './dist/infinite-plugin.zip',
					mode: 'zip',
				},
				files: [
					{ src: './admin/**' },
					{ src: './config/index.php' },
					{ src: './extensions/index.php' },
					{ src: './icons/**' },
					{ src: './includes/**' },
					{ src: './languages/**' },
					{ src: './public/**' },
					{ src: './sql/index.php' },
					{ src: './postcss.config.js' },
					{ src: './tw-admin.config.js' },
					{ src: './tw-public.config.js' },
					{ src: './composer.json' },
					{ src: './package.json' },
					{ src: './README.md' },
					{ src: './index.php' },
					{ src: './infinite.php' },
					{ src: './uninstall.php' },
					{ src: './LICENSE.txt' },
				],
			},
		},
	});

	// Load grunt plugins
	grunt.loadNpmTasks('grunt-contrib-compress');

	// Register tasks
	grunt.registerTask('default', ['compress']);
};