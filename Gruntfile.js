module.exports = function(grunt) {

	const sass = require('node-sass');
	require('load-grunt-tasks')(grunt);

	// Project configuration.
	grunt.initConfig({

		// Setting folder templates.
		dirs: {
			css: 'assets/css',
			js: 'assets/js',
			php: 'includes'
		},

		pkg: grunt.file.readJSON('package.json'),
		
		// JavaScript linting
		jshint: {
			options: {
				reporter: require('jshint-stylish'),
				globals: {
					"EO_SCRIPT_DEBUG": false,
				},
				'-W020': true, //Read only - error when assigning EO_SCRIPT_DEBUG a value.
			},
			all: [
				'<%= dirs.js %>/*.js',
				'!<%= dirs.js %>/*.min.js'
			]
		},

		// Sass linting with Stylelint.
		stylelint: {
			options: {
				configFile: '.stylelintrc'
			},
			all: [
				'<%= dirs.css %>/*.scss'
			]
		},

		// Minify .js files.
		uglify: {
			options: {
				ie8: true,
				parse: {
					strict: false
				},
				output: {
					comments : /@license|@preserve|^!/
				}
			},
			js_assets: {
				files: [{
					expand: true,
					cwd: '<%= dirs.js %>/',
					src: [
						'**/*.js',
						'!**/*.min.js'
					],
					extDot: 'last',
					dest: '<%= dirs.js %>',
					ext: '.min.js'
				}]
			}
		},

		// Compile all .scss files.
		sass: {
			compile: {
				options: {
					implementation: sass,
					sourceMap: 'none'
				},
				files: [{
					expand: true,
					cwd: '<%= dirs.css %>/',
					src: ['*.scss'],
					dest: '<%= dirs.css %>/',
					ext: '.css'
				}]
			}
		},

		// Generate RTL .css files.
		rtlcss: {
			plugin: {
				expand: true,
				cwd: '<%= dirs.css %>',
				src: [
					'*.css',
					'!*-rtl.css'
				],
				dest: '<%= dirs.css %>/',
				ext: '-rtl.css'
			}
		},

		// Minify all .css files.
		cssmin: {
			minify: {
				files: [
					{
						expand: true,
						cwd: '<%= dirs.css %>/',
						src: ['*.css'],
						dest: '<%= dirs.css %>/',
						ext: '.css'
					}
				]
			}
		},

		// Watch changes for assets.
		watch: {
			css: {
				files: ['<%= dirs.css %>/*.scss'],
				tasks: ['sass', 'rtlcss', 'postcss', 'cssmin']
			},
			js: {
				files: [
					'GruntFile.js',
					'<%= dirs.js %>/**/*.js',
					'!<%= dirs.js %>/**/*.min.js'
				],
				tasks: ['jshint','newer:uglify']
			}
		},

		// Generate git readme from readme.txt
		wp_readme_to_markdown: {
			convert: {
				files: {
					'readme.md': 'readme.txt'
				},
			},
		},

		// Watch changes for assets.
		watch: {
			css: {
				files: [
				'assets/css/*.scss'
				],
				tasks: ['sass', 'rtlcss', 'cssmin']
			},
			js: {
				files: [
				'assets/js/*.js',
				'!assets/js/*.min.js',
				],
				tasks: ['jshint', 'uglify']
			}
		},

		// Autoprefixer.
		postcss: {
			options: {
				processors: [
					require( 'autoprefixer' )
				]
			},
			dist: {
				src: [
					'<%= dirs.css %>/*.css'
				]
			}
		}

	});

	// Register tasks.
	grunt.registerTask( 'default', [
		'js',
		'css'
	]);

	grunt.registerTask( 'js', [
		'jshint',
		'uglify:js_assets'
	]);

	grunt.registerTask( 'css', [
		'sass',
		'rtlcss',
		'postcss',
		'cssmin'
	]);

	grunt.registerTask( 'assets', [
		'js',
		'css'
	]);

	// Only an alias to 'default' task.
	grunt.registerTask( 'dev', [
		'default'
	]);
};
