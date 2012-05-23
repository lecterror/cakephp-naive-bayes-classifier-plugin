# CakePHP NaiveBayesClassifier Plugin #

## About ##

NaiveBayesClassifier is a [CakePHP][] plugin which enables you to classify text
by using the [Naive Bayes Classifier][]. This is not a spam filter, but a general
purpose classifier. However, it can serve as a spam filter if configured and
trained to do so, either independently or as a part of a larger spam filtering system.

Known issues: Was not tested on large data sets, so I have no idea how will it scale.
Probably not very well, due to some limitations I had, but caching will probably have
to be implemented in one of the future versions of the plugin.

## Usage ##

**Important**: Minimum requirements for this plugin: `CakePHP 2.2+`.

Obtain the plugin and put it in your Plugin folder. If you're using Git, you can run this
while in your app folder:

	git submodule add git://github.com/lecterror/cakephp-naive-bayes-classifier-plugin.git Plugin/NaiveBayesClassifier
	git submodule init
	git submodule update

Or visit <http://github.com/lecterror/cakephp-naive-bayes-classifier-plugin> and download the
plugin manually to your `app/Plugin/NaiveBayesClassifier/` folder.

Also, don't forget to activate the plugin in your application config (see [Installing a Plugin][] in
the CakePHP Cookbook).

Next, you need to create the database tables needed by the plugin:

	cake schema create --plugin NaiveBayesClassifier

There are two ways to use the plugin: by using the NaiveBayesClassifier.Classifier component or
by using the NaiveBayesClassifier.Classifiable behaviour. The component is used for training and
untraining the classifier, as well as classifying itself, while the behaviour is only used for
automatic classification during saving or validation.

Component usage:

	class ExampleController extends Controller
	{
		public $components = array('NaiveBayesClassifier.Classifier');

		public function index()
		{
			$this->Classifier->train('enlarge your watch', 'spam');

			$class = $this->Classifier->classify('enlarge');

			$this->Classifier->untrain('enlarge your watch', 'spam');
		}
	}

Behaviour usage 1 - automatic classification on save:

	class Something extends AppModel
	{
		public $actsAs = array
			(
				'NaiveBayesClassifier.Classifiable' => array
				(
					'on' => 'save',
					'classify' => array('one', 'or_more', 'fields', 'to_classify'),
					'destination' => 'class_field', // where the class will be stored
				)
			);
	}

Behaviour usage 2 - validation based on class:

	class Something extends AppModel
	{
		public $actsAs = array
			(
				'NaiveBayesClassifier.Classifiable' => array
				(
					'on' => 'validate',
					'classify' => array('one', 'or_more', 'fields', 'to_classify'),
					'destination' => 'class_field', // where the class will be stored
					'valid_class' => 'ham', // class(es) which will not fail validation
				)
			);
	}

You're done! For more options and usage examples, check out the plugin source for function
descriptions which describe all the options, such as Laplace smoothing and class mapping for
storing into database. Additionally, you can check out the unit tests which provide full
examples on how to use the plugin with (hopefully) all options.

## Contributing ##

If you'd like to contribute, clone the source on GitHub, make your changes and send me a pull request.
If possible, always include unit tests for your modifications. If you're reporting a bug, a failing
unit test might help resolve the issue much faster than usual. If you don't know how to fix the issue
or you're too lazy to do it, create a ticket and we'll see what happens next.

I am always open to new ideas and suggestions.

**Important**: If you're sending a patch, follow the coding style! If you don't, there is a great
chance I won't accept it. For example:

	// bad
	function drink() {
		return false;
	}

	// good
	function drink()
	{
		return true;
	}

## Licence ##

Multi-licenced under:

* MPL <http://www.mozilla.org/MPL/MPL-1.1.html>
* LGPL <http://www.gnu.org/licenses/lgpl.html>
* GPL <http://www.gnu.org/licenses/gpl.html>

[CakePHP]: http://cakephp.org/
[Naive Bayes Classifier]: http://en.wikipedia.org/wiki/Naive_Bayes_classifier
[Installing a plugin]: http://book.cakephp.org/2.0/en/plugins.html#installing-a-plugin
