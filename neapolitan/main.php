<?php
$source_string = "gtgtsgipgttptinggipsppaigsesgpetgstpatetisiesagaeaigttetepitiatsegssieeeeatepaaiagtpieataatppiitgiapsteitatiiatpetetetttgpetpaasipttssstpeeeggtiagtttegtiipestsasgpsepaasapttgattgiatppegitiatpasgatgepttggapesaeetaeissttggieietgspagesiipestipggstttpatep
titiaetottissgggtttaipappgstsptttgtpispattgegstltiappseisapgistaiagteeiptptpisaieisagstapeteietgteiisgtiptstgtstasspeatspptitttatteastsgtptgtasggpniaaeteaisett";
$target_word = "neapolitan";
$target_word_length = strlen($target_word);

$split_strings = array();
$tmp_string = $source_string;
for( $i = 0; $i < $target_word_length; $i++ ){
  $character = $target_word[$i];
  $found_pos = strpos( $tmp_string, $character );
  $left_string = substr( $tmp_string, 0, $found_pos );
  $right_string = substr( $tmp_string, $found_pos+1 );
  $split_strings[] = array( $character, $left_string );

  if( $i == ($target_word_length - 1) ){
    $split_strings[] = array( '', $right_string );
  }

  // next search string
  $tmp_string = $right_string;
}

$result_string = "";
foreach( $split_strings as $string ){
  $result_string .= "{$string[1]}";
  if( ! empty($string[0]) ){
    $result_string .= "[{$string[0]}]";
  }
}

echo $result_string."\n";
