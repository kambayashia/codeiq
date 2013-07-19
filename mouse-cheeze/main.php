<?php
// マップ横幅
const MAP_WIDTH = 5;
// マップ縦幅
const MAP_HEIGHT = 5;
// チーズを配置したマップ
$MASTER_MAP = array(
    0, 0, 1, 0, 0,
    0, 0, 1, 0, 1,
    1, 1, 0, 0, 0,
    0, 0, 0, 1, 0,
    1, 1, 0, 0, 0,
);
// ネズミのスタート地点
$START_POINT = make_node( null, make_point( 0, 0 ) );
// ネズミ自身からの相対的な移動量
$MOVABLE_POINT = array(
    make_point( -1, 2 ), // 南南西
    make_point(  1, 2 ), // 南南東
    make_point( 2,  1 ), // 東南東
    make_point( 2, -1 ), // 東北東
    make_point( -1, -2 ), // 北北西
    make_point(  1, -2 ), // 北々東
    make_point( -2,  1 ), // 西南西
    make_point( -2, -1 ), // 西北西
);

$current_map = array();

//=================================================
// マップ型
//=================================================
/**
 * 移動ずみマップの初期化
 */
function init_current_map(){
  global $current_map;
  global $MASTER_MAP;
  $current_map = $MASTER_MAP;
}

/**
 * 指定位置のマップ情報取得
 */
function get_master_map_info( $point ){
  global $MASTER_MAP;
  return $MASTER_MAP[ $point['x'] + ($point['y'] * MAP_WIDTH) ];
}

/**
 * 指定位置の移動済情報取得
 */
function get_current_map_info( $point ){
  global $current_map;
  return $current_map[ $point['x'] + ($point['y'] * MAP_WIDTH) ];
}

/**
 * 指定位置の移動済マップに情報を入れる
 */
function set_current_map_info( $point, $state ){
  global $current_map;
  return $current_map[ $point['x'] + ($point['y'] * MAP_WIDTH) ] = $state;
}

/**
 * 指定位置はすでに通ったか
 */
function is_moved( $point ){
  return get_current_map_info( $point ) == 2;
}

/**
 * 移動可能か
 */
function is_movable( $current, $offset ){
  $result = true;
  $point = add_point( $current, $offset );
  if( ($point['x'] < 0) || ($point['y'] < 0) ||
      ($point['x'] >= MAP_WIDTH) || ($point['y'] >= MAP_HEIGHT) ||
      is_moved( $point ) ){
    $result = false;
  }

  return $result;
}

/**
 * 移動
 */
function move( $current_node, $offset ){
  $new_point = add_point( $current_node['point'], $offset );
  $map_state = get_current_map_info( $new_point );
  set_current_map_info( $new_point, 2 );
  return make_node( $current_node, $new_point, $point_state == 1);
}

/**
 * 探索
 */
function search( $current_node ){
  global $MOVABLE_POINT;
  global $current_map;
  foreach( $MOVABLE_POINT as $offset ){
    if( is_movable( $current_node['point'], $offset ) ){
      $next_node = move( $current_node, $offset );
      if( get_cheeze_count( $current_map
      search( $next_node );
    }
  }
}

/**
 * 集計
 */
function 
//=================================================
// その他こまごましたの
//=================================================
/**
 * 座標作成
 *
 */
function make_point( $x, $y ){
  return array( 'x' => $x, 'y' => $y );
}

/**
 * 座標の加算
 */
function add_point( $point1, $point2 ){
  return array(
      'x' => $point1['x'] + $point2['x'],
      'y' => $point1['y'] + $point2['y']
  );
}

/**
 * ルートの通り道になる地点作成
 */
    function make_node( $parent, $point, $has_cheeze = false){
  $new_node = array(
      'childs' => array(),
      'parent' => $parent,
      'point' => $point,
      'has_cheeze' => $has_cheeze,
  );
  return $new_node;
}

//=================================================
// 実際の処理
//=================================================
echo "start\n";
init_current_map();
echo "end\n";

