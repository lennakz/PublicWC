<?php

function d($data)
{
	echo '<pre>';
	var_dump($data);
	echo '</pre>';
}

function getData($url, $output_file)
{
	$counter = 0;
	$keys = [];
	$places = [];
	
	if (!file_exists($output_file))
	{
		$fp = fopen($output_file, 'a+');
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FILE, $fp);

		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}
	
	if (($fp = fopen($output_file, 'r')) !== false)
	{
		while (($data = fgetcsv($fp)) !== false)
		{
			if ($counter++ === 0)
			{
				$keys = $data;
				continue;
			}

			foreach ($data as $k => $field)
			{
				if (empty($field))
					$field = '(no info)';
				
				$array[strtolower($keys[$k])] = $field;
			}

			$places[] = $array;

		}
		fclose($fp);
	}
	
	$places = json_encode($places);
	
	return $places;
}