<?php
/**
 * Перевіряє чи є елемент в списку, 
 * якщо немає то присвоює йому значення 1го ([0]) елемента зі списку
 */
function isInList(&$item, $list)
{
	$langIsInList = false;
	foreach($list as $i)
		if ($item == $i)
		{
			$langIsInList = true;
			break;
		}
	if(!$langIsInList)
		$item = $list[0];
	return $langIsInList;
}
/**
 * Заповнення контенту
 */
function SetMainContent($name, $Dir)
{
	$file = fopen("./Lang/".$Dir."/".$name.".txt", "r");
	if ($file!=false)
	{
		while(!feof($file))
		{
			echo fgets($file);/*."<br />";*/
		}
	}
	else
	{
		echo "./Lang/".$Dir."/".$name.".txt";
	}
	fclose($file);
}
function parseInputString($inputString, $tagList)
{	
	$phpVersion = phpversion();
	
	//array for storing parse result data
	$data = array();
	
	//parsing loop...
	foreach($tagList as $tlItem)
	{
		$tagStartLen = strlen("<*".$tlItem."*>");
		$tagEndLen = strlen("<*\\".$tlItem."*>");
		
		//position of startTag
		$tagStartPos = strpos($inputString, "<*".$tlItem."*>");
		
		//if true: there is no start tag in the string OR position == 0
		if($tagStartPos == FALSE)
		{
			//if true: position == 0
			if(!($tagStartPos === FALSE))
			{
				//position of endTag
				$tagEndPos = strpos($inputString, "<*/".$tlItem."*>");
				
				//if true: there is no endTag for this tag - throw exception
				if($tagEndPos === FALSE)
					if($phpVersion[0] >= 5)
						eval("throw new LogicException(\"Page content error. There is no end tag for \".\$tlItem)");
					else
						return array("exception" => "Page content error. There is no end tag for ".$tlItem);
				
				
				//if($tagEndPos < $tagStartPos)
				//	throw new LogicException("Page content error. Incorrect sequence of ".$tlItem." tags(end/start).");
				
				//*uncommented* endTag search loop...
				while($inputString[$tagEndPos-1] == '\\')
				{
					
					//remove '\' from beginning of *commented* tags
					$tmpStr = substr($inputString, 0, $tagEndPos - 1);
					$tmpStr .= substr($inputString, $tagEndPos);
					$inputString = $tmpStr;
					//and decrease $tagEndPos
					$tagEndPos -= 1;
					
				
					$tmpPos = $tagEndPos + $tagEndLen;
					$tagEndPos = strpos(substr($inputString, $tagEndPos + $tagEndLen), "<*/".$tlItem."*>");
					
					//if true: there is no *uncommented* endTag for this tag - throw exception
					if($tagEndPos === FALSE)
						if($phpVersion[0] >= 5)
							eval("throw new LogicException(\"Page content error. There is no *uncommented* end tag for '\".\$tlItem.\"' tag.\")");
						else
							return array("exception" => "Page content error. There is no *uncommented* end tag for '".$tlItem."' tag.");
					
					$tagEndPos += $tmpPos;
				}
			}
			//there is no such tag in the string
			else
			{
				//throw new LogicException("Page content error. There is no start tag for ".$tlItem);
				continue;
			}
		}
		//position of start tag > 0
		else
		{
			//*uncommented* startTag search loop...
			$isStartTag = true;
			while($inputString[$tagStartPos-1] == '\\')
			{
				/*
				//remove '\' from beginning of *commented* tags
				$tmpStr = substr($inputString, 0, $tagStartPos - 1);
				$tmpStr .= substr($inputString, $tagStartPos);
				$inputString = $tmpStr;
				//and decrease $tagStartPos
				$tagStartPos -= 1;
				*/
			
				$tmpPos = $tagStartPos + $tagStartLen;
				$tagStartPos = strpos(substr($inputString, $tagStartPos + $tagStartLen), "<*".$tlItem."*>");
				
				//if true: there is no *uncommented* startTag for this tag - throw exception
				if($tagStartPos === FALSE)
				{
					//throw new LogicException("Page content error. There is no *uncommented* start tag for ".$tlItem);
					$isStartTag = false;
					break;
				}
					
				$tagStartPos += $tmpPos;
			}
			//if there is no startTag continue to the next tag
			if(!$isStartTag)
				continue;			
			
			//search endTag after appearance of startTag in string
			$tagEndPos = strpos(substr($inputString, $tagStartPos + $tagStartLen), "<*/".$tlItem."*>");
			
			//if true: there is no endTag for this tag - throw exception
			if($tagEndPos === FALSE)
				if($phpVersion[0] >= 5)
					eval("throw new LogicException(\"Page content error. There is no end tag for \".\$tlItem)");
				else
					return array("exception" => "Page content error. There is no end tag for ".$tlItem);
				
			$tagEndPos += $tagStartPos + $tagStartLen;
			
			//*uncommented* endTag search loop...
			while($inputString[$tagEndPos-1] == '\\')
			{
				
				//remove '\' from beginning of *commented* tags
				$tmpStr = substr($inputString, 0, $tagEndPos - 1);
				$tmpStr .= substr($inputString, $tagEndPos);
				$inputString = $tmpStr;
				//and decrease $tagEndPos
				$tagEndPos -= 1;
				
			
				$tmpPos = $tagEndPos + $tagEndLen;
				$tagEndPos = strpos(substr($inputString, $tagEndPos + $tagEndLen), "<*/".$tlItem."*>");
				
				//if true: there is no *uncommented* endTag for this tag - throw exception
				if($tagEndPos === FALSE)
					if($phpVersion[0] >= 5)
						eval("throw new LogicException(\"Page content error. There is no *uncommented* end tag for \".\$tlItem)");
					else
						return array("exception" => "Page content error. There is no *uncommented* end tag for ".$tlItem);
				
				$tagEndPos += $tmpPos;
			}
		}
		
		//copy data to resulting array
		$data[$tlItem] = substr($inputString, $tagStartPos + $tagStartLen, $tagEndPos - ($tagStartPos + $tagStartLen));
		
		//cut copied data from string AND *used* tags
		$tmpStr = substr($inputString, 0, $tagStartPos);
		$tmpStr .= substr($inputString, $tagEndPos + $tagEndLen);
		$inputString = $tmpStr;
	}
	
	//add remained string to the end od resulting array
	$data["remained"] = $inputString;
	
	return $data;
}
?>