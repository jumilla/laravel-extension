<?php namespace LaravelPlus\Extension;

class PhpSettingGenerator {

	public static function generateFile($filepath, array $settings)
	{
		file_put_contents($filepath, static::generateText($settings));
	}

	public static function generateText(array $settings)
	{
		$instance = new static;
		return $instance->generate($settings);
	}

	public function generate(array $settings)
	{
		$this->text = "<?php\n\nreturn [\n";
		$this->indent = 0;

		$this->generateArray($settings);

		$this->writeLine('];');

		return $this->text;
	}

	private function generateArray(array $settings)
	{
		++$this->indent;

		foreach ($settings as $key => $value) {
			if (is_null($value)) {
				if (is_string($key))
					$this->writeLine(sprintf("'%s' => %s,", $key, 'null'));
				else
					$this->writeLine(sprintf("%s,", 'null'));
			}
			else if (is_bool($value)) {
				if (is_string($key))
					$this->writeLine(sprintf("'%s' => %s,", $key, $value ? 'true' : 'false'));
				else
					$this->writeLine(sprintf("%s,", $value ? 'true' : 'false'));
			}
			else if (is_string($value)) {
				if (is_string($key))
					$this->writeLine(sprintf("'%s' => '%s',", $key, $value));
				else
					$this->writeLine(sprintf("'%s',", $value));
			}
			else if (is_array($value)) {
				if (is_string($key))
					$this->writeLine(sprintf("'%s' => [", $key));
				else
					$this->writeLine('[');

				$this->generateArray($value);

				$this->writeLine('],');
			}
			else {
				if (is_string($key))
					$this->writeLine(sprintf("'%s' => %s,", $key, $value));
				else
					$this->writeLine($value.',');
			}
		}

		--$this->indent;
	}

	private function writeLine($line)
	{
		$this->text .= str_repeat("\t", $this->indent);
		$this->text .= $line;
		$this->text .= "\n";
	}

}
