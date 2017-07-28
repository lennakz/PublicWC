<?php

function d($data)
{
	echo '<pre>';
	var_dump($data);
	echo '</pre>';
}

function getData()
{
	$file = 'data.txt';
	$data_url = 'ftp://webftp.vancouver.ca/OpenData/csv/public_washrooms.csv';
	$counter = 0;
	$keys = [];
	$toilets = [];
	
	if (!file_exists($file))
	{
		$fp = fopen($file, 'a+');
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $data_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FILE, $fp);

		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}
	
	if (($fp = fopen($file, 'r')) !== false)
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

			$toilets[] = $array;

		}
		fclose($fp);
	}
	
	$toilets = json_encode($toilets);
	
	return $toilets;
}