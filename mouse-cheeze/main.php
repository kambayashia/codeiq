<?php
// マップ横幅
const MAP_WIDTH = 5;
// マップ縦幅
const MAP_HEIGHT = 5;
// チーズを配置したマップ
$MASTER_MAP = array(
    2, 0, 1, 0, 0,
    0, 0, 1, 0, 1,
    1, 1, 0, 0, 0,
    0, 0, 0, 1, 0,
    1, 1, 0, 0, 0,
);
// ネズミのスタート地点
$START_POINT = make_node( null, make_point( 0, 0 ), 1 );
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
$MOST_SHORT_PATH = array( 
    'node' => null,
    'hop' => 10000, //ありえない経路数
);

    
//=================================================
// マップ型
//=================================================
/**
 * 指定位置のマップ情報取得
 */
function get_master_map_info( $point ){
  global $MASTER_MAP;
  return get_map_info( $MASTER_MAP );
}

/**
 * 指定位置の移動済情報取得
 */
function get_map_info( $map, $point ){
  return $map[ $point['x'] + ($point['y'] * MAP_WIDTH) ];
}

/**
 * 指定位置の移動済マップに情報を入れる
 */
function set_map_info( &$map, $point, $state ){
  $map[ $point['x'] + ($point['y'] * MAP_WIDTH) ] = $state;
}

/**
 * 指定位置はすでに通ったか
 */
function is_moved( $map, $point ){
  return get_map_info( $map, $point ) == 2;
}

/**
 * 残りチーズ数
 */
function get_cheeze_count( $map ){
  $count = 0;
  foreach( $map as $state ){
    if( $state == 1 ){
      $count++;
    }
  }
  return $count;
}

/**
 * 移動可能か
 */
function is_movable( $map, $current, $offset ){
  $result = true;
  $point = add_point( $current, $offset );
  if( ($point['x'] < 0) || ($point['y'] < 0) ||
      ($point['x'] >= MAP_WIDTH) || ($point['y'] >= MAP_HEIGHT) ||
      is_moved( $map, $point ) ){
    $result = false;
  }

  return $result;
}

/**
 * 移動
 */
function move( &$map, $current_node, $offset ){
  $new_point = add_point( $current_node['point'], $offset );
  $map_state = get_map_info( $map, $new_point );
  set_map_info( $map, $new_point, 2 );
  return make_node( $current_node, $new_point, $current_node['hop'] + 1, $map_state == 1);
}

/**
 * 探索
 */
function search( $map, &$current_node ){
  global $MOVABLE_POINT;
  global $MOST_SHORT_PATH;

  if( $current_node['hop'] >= $MOST_SHORT_PATH['hop'] ){
    return;
  }

  foreach( $MOVABLE_POINT as $offset ){
    $tmp_map = $map;
    if( is_movable( $tmp_map, $current_node['point'], $offset ) ){
      $next_node = move( $tmp_map, $current_node, $offset );
      if( get_cheeze_count( $tmp_map ) <= 0 ){
        $MOST_SHORT_PATH['node'] = $next_node;
        $MOST_SHORT_PATH['hop'] = $next_node['hop'];

        print_node( $next_node );
        break;
      }

      search( $tmp_map, $next_node );
    }
  }
}

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
function make_node( $parent, $point, $hop, $has_cheeze = false){
  $new_node = array(
      'parent' => $parent,
      'point' => $point,
      'hop' => $hop,
      'has_cheeze' => $has_cheeze,
  );

  return $new_node;
}

/**
 * 経路表示
 */
function print_node( $node ){
  $has_cheeze_prefix = $node['has_cheeze'] ? "*" : " ";
  echo "{$has_cheeze_prefix}({$node['point']['x']},{$node['point']['y']})";
  if( isset( $node['parent'] ) ){
    echo "->";
    print_node( $node['parent'] );
  }
  else{
    echo "\n";
  }
//  if( isset( $node['parent'] ) ){
//    print_node( $node['parent'] );
//  }
//  echo "{$node['point']['x']},{$node['point']['y']}\n";
}

//=================================================
// 実際の処理
//=================================================
search( $MASTER_MAP, $START_POINT );

echo "\n\n";
echo "answer\n";
print_node( $MOST_SHORT_PATH['node'] );
