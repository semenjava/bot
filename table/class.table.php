<?php

/**
 * Table
 *
 * Automatic creation of html tables from the data array
 */
class Table {

    /**
     * @var array $info Should contain a common table info and settings
     */
    private static $info = null;

    /**
     * @var array $html Should contain a temporary HTML code of table
     */
    private static $html = null;

    /**
     * @var array $ungroupedRows Should contain a temporary ungrouped rows
     */
    private static $ungroupedRows = null;

    /**
     * @var active sheet of PHPExcel
     */
    private static $sheet = null;

    /**
     * @var excelActiveCellCoord of PHPExcel
     */
    private static $acc = null;

    /**
     * @var value for closure index function
     */
    public static $index = null;
    public static $view = null;

    /**
     * Synonym of Table::writeTable()
     * Return html table from data array
     *
     * @see writeTable()
     * @param array $data array of data
     * @param array $tableInfo array of common table info and settings
     * @return void
     */
    public static function html($data = false, $donwFileHref = false, $tableInfo = false ) {
        $_SESSION['class.table']['count'] = 0;
        return self::writeTable($data, $tableInfo);
    }

    /**
     * Return html table from data array
     *
     * @param array $data array of data
     * @param array $tableInfo array of common table info and settings
     * @return void
     */
    private static function writeTable($data = false, $tableInfo = false) {
        if ($data) {
            $args = "";
            $colKeys = false;
            $titles = false;
            $href = '';

            //Search and collect information about the table in $tableInfo
            //High priority is given to $tableInfo(second parameter)
            if (!$tableInfo)
                $tableInfo = $data['tableInfo'];
            elseif ($data['tableInfo'])
                $tableInfo = array_replace_recursive($data['tableInfo'], $tableInfo);

            $data['tableInfo'] = $tableInfo;
            $date = new DateTime('now');
            $_SESSION['class.table']['date'] = $date;
            $_SESSION['class.table']['array'] = $data;
            $_SESSION['class.table']['count']++;
            $tableNum = $_SESSION['class.table']['count'];

            unset($data['tableInfo']);

            if ($tableInfo) {
                if(!empty($donwFileHref)) {
                    $href = $donwFileHref;
                }
                
                //Following work will be happening with global variable $info
                self::$info = $tableInfo;
                unset($tableInfo);
                
                

                //Collecting information about columns(titles and column keys)
                if (self::$info['cols']) {
                    self::$info['cols'] = array_filter(
                            self::$info['cols'],
                            function($v) {
                            $useCol = ( (!empty($v['show']) && $v['show'] == "excel" ) ? false : true) && (is_string($v) || is_bool($v) ? $v : $v['title']);
                            return $useCol;
                        }
                    );
                    $colKeys = array_keys(self::$info['cols']);
                    $titles = array_filter(
                            array_map(
                                    function($v) {
                                return is_string($v) ? $v : (is_string($v['title']) ? $v['title'] : false);
                            },
                                    self::$info['cols']
                            )
                    );
                    self::$view = array();
                    foreach (self::$info['cols'] as $k => $col)
                        if (is_array($col) && isset($col['view'])) {
                            self::$view[$k] = $col['view'];
                            if (is_array($_SESSION['class.table']['array']['tableInfo']['cols'][$k]))
                                unset($_SESSION['class.table']['array']['tableInfo']['cols'][$k]['view']);
                        }

//					self::pre(self::$view);
                    unset(self::$info['cols']);
                }
                
                if(empty(self::$info['class'])) {
                    self::$info['class'] = '';
                }
            }

            
            self::$info['class'] = trim("class.table " . self::$info['class']);
            
            
            
            //Collecting HTML parameters for table
            $args = self::convertRulesToHtml(self::$info);

            //Height of each row will be calculated automatically by the default
            if (!isset(self::$info['rowspan']))
                self::$info['rowspan'] = true;
            
            self::$html .= '<link media="screen" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet">';
            self::$html .= '<script src="http://code.jquery.com/jquery-1.8.3.js"></script>';
            self::$html .= '<script type="text/javascript" async="" src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>';
            self::$html .= '<script type="text/javascript" async="" src="/table/class.table.js"></script>';
            
            
            self::$html .= "\n		<table{$args}>";
            self::$html .= "\n			<caption>
                                                        <div class='forToggle'>
                                                        <a href='{$href}'>Скачать в csv</a>
                                                                <!--<button title='Генерирует последнюю запрошенную таблицу'>
                                                                        Сохранить
                                                                </button>
                                                                <div class='tableSettings' title='Доступно только если сгруппирована текущая таблица'>
                                                                        <input type='checkbox' checked='checked' id='table{$tableNum}' value='value'><label for='table{$tableNum}'>Группировать таблицу</label>
                                                                </div>-->
                                                        </div>
                                                </caption>";

            //If title was set then write
            if (is_array($titles) && sizeof($titles)) {
                self::$html .= "\n  <thead>";
                self::$html .= "\n		<tr>";
                foreach ($titles as $title) {
                    self::$html .= "\n			<th>{$title}</th>";
                }
                self::$html .= "\n		</tr>";
                self::$html .= "\n  </thead>";
            }

            //Write the rows
            self::$html .= "\n  <tbody>";
            foreach ($data as $info) {
                self::writeRow($info, $colKeys);
            }
            self::$html .= "\n  </tbody>";
            
            if (is_array($titles) && sizeof($titles)) {
                self::$html .= "\n  <tfoot>";
                self::$html .= "\n		<tr>";
                foreach ($titles as $title) {
                    self::$html .= "\n			<th>{$title}</th>";
                }
                self::$html .= "\n		</tr>";
                self::$html .= "\n  </tfoot>";
            }

            self::$html .= "\n		</table>";

            $html = self::$html;
            //Clearing of global variables for the next use a static class
            self::$info = null;
            self::$view = null;
            self::$html = null;

            return $html;
        }
    }

    /**
     * Write row of table from data array
     *
     * @param array $data array of data
     * @param array $colKeys array of column keys. Using for manage output order
     * @return void
     */
    private static function writeRow($data, $colKeys) {
        if (is_array($data)) {
            $rowRules = false;
            $cellsRules = false;
            //====================
            $rowspan = self::$info['rowspan'];
            $countRows = false;
            $args = "";
            //====================
            $subRow = false;

            if (!empty($data['tableInfo'])) {
                $rowRules = $data['tableInfo'];
                unset($data['tableInfo']);

                if ($rowRules['cols']) {
                    $cellsRules = $rowRules['cols'];
                    unset($rowRules['cols']);
                }

                if ($rowRules['rowspan']) {
                    $rowspan = $rowRules['rowspan'];
                    unset($rowRules['rowspan']);
                }

                $colKeys = self::modifyKeys($colKeys, $rowRules['keys']);

                $args = self::convertRulesToHtml($rowRules);
            }

            $subRow = array_values(array_filter($data, function($v) {
                        return is_array($v);
                    }));
            $subRow = !empty($subRow[0]) ? $subRow[0] : [];

            //If do not specify column keys then it is filled with all the elements of the array, except for sub-arrays(all cells)
            if (!$colKeys)
                $colKeys = array_keys(array_filter($data, function($v) {
                            return !is_array($v);
                        }));

            self::$html .= "\n		<tr{$args}>";
            foreach ($colKeys as $i => $key) {
                if (
                        isset($data[$key]) && //$subRow &&
                        (
                        (!isset($cellsRules[$key]['rowspan']) && $rowspan) || (is_bool($cellsRules[$key]['rowspan']) && $cellsRules[$key]['rowspan'])
                        )
                ) {
                    if (!$countRows && empty($countRows)) {
                        if((is_int($rowspan)))
                            $countRows =  $rowspan;  
                        else 
                            $countRows =  self::countRows($data);
                    }
                        
                    if (empty($cellsRules[$key]) 
                            && empty($cellsRules[$key]['rowspan']) )
                        $cellsRules[$key]['rowspan'] = $countRows;
                }
                if (array_key_exists($key, $data)) {
                    unset($colKeys[$i]);

                    if (isset(self::$view[$key])) {
                        $view = call_user_func(
                                self::$view[$key],
                                array(
                                    'data' => $data,
                                    'rules' => $cellsRules[$key]
                                )
                        );

                        if (is_array($view)) {
                            $data[$key] = $view['data'] ?: $data['key'];
                            $cellsRules[$key] = $view['rules'] ?: $cellsRules[$key];
                        } else
                            $data[$key] = $view;
                    }
                }

                self::writeCell($data[$key], $cellsRules[$key]);
            }
            self::$html .= "\n		</tr>";

            if(!empty($subRow)) {
                foreach ($subRow as $row)
                    self::writeRow($row, $colKeys);
            }
        }
    }

    /**
     * Write cell of table from data array
     *
     * @param string $data cell content
     * @param array $rules parameters of cell
     * @return void
     */
    private static function writeCell($data, $rules) {
        $args = self::convertRulesToHtml($rules);
        self::$html .= "\n			<td{$args}>{$data}</td>";
    }

    /**
     * Count the number of subrows
     *
     * @param array $data array of subrows
     * @return int
     */
    private static function countRows($data, $toExcel = false) {
        $rows = 0;

        if (is_array($data)) {
            unset($data['tableInfo']);
            $rows = 1;

            foreach ($data as $item)
                if (is_array($item)) {
                    if ($toExcel)
                        $rows--;
                    foreach ($item as $a)
                        $rows += self::countRows($a, $toExcel);
                }
        }

        return $rows;
    }

    /**
     * Convert array of parameters to html string
     *
     * @param array $info array of table/row/cell parameters
     * @return string
     */
    private static function convertRulesToHtml($info) {
        $args = "";
        
        if(empty($info['colspan'])) 
            $info['colspan'] = 0;
        if(empty($info['rowspan'])) 
            $info['rowspan'] = 0;
             

        if (is_int($info['colspan']))
            $args .= " colspan='{$info['colspan']}'";
        if (is_int($info['rowspan']) && $info['rowspan'] > 1)
            $args .= " rowspan='{$info['rowspan']}'";
        if (!empty($info['id']))
            $args .= " id='{$info['id']}'";
        if (!empty($info['class']))
            $args .= " class='{$info['class']}'";
        if (!empty($info['style']))
            $args .= " style='{$info['style']}'";
        if (!empty($info['args']))
            $args .= " {$info['args']}";

        return $args;
    }

    /**
     * Modify column keys by rules
     *
     * @param array $colKeys array of column keys
     * @param array $rules array of modification rules
     * @return array
     */
    private static function modifyKeys($colKeys, $rules) {
        if ($rules) {
            if ($rules['delete'] == 'all')
                $colKeys = array();
            else
                foreach ($rules['delete'] as $v)
                    if (is_int(array_search($v, $colKeys)))
                        unset($colKeys[array_search($v, $colKeys)]);

            foreach ($rules['add'] as $v)
                $colKeys[] = $v;

            foreach ($rules['forwarding'] as $v)
                if (array_search($v['src'], $colKeys))
                    $colKeys[array_search($v['src'], $colKeys)] = $v['dst'];
        }

        return array_values($colKeys);
    }

    public static function groupArray($data) {
        if (is_array($data)) {
            $byCols = array();
            if (isset($data['tableInfo']['groupBy'])) {
                $byCols = $data['tableInfo']['groupBy'];
                unset($data['tableInfo']['groupBy']);
            } else {
                $byCols = array_map(
                        function ($v) {
                    return $v['group'];
                },
                        array_filter(
                                $data['tableInfo']['cols'],
                                function ($v) {
                            return is_array($v) && isset($v['group']);
                        }
                        )
                );
                if ($byCols)
                    foreach ($data['tableInfo']['cols'] as $k => $v)
                        if (is_array($v) && isset($v['group']))
                            unset($data['tableInfo']['cols'][$k]['group']);
            }

            if ($byCols) {
                $newData = array();

                $tableInfo = $data['tableInfo'];
                unset($data['tableInfo']);

                reset($byCols);
                $currColK = key($byCols);
                $mergeClosure = $byCols[$currColK]['mergeClosure'];
                foreach ($data as $key => $item) {
                    if (!$newData[$item[$currColK]])
                        $newData[$item[$currColK]] = array();

                    $newData[$item[$currColK]] = ($mergeClosure) ? call_user_func_array($mergeClosure, array($newData[$item[$currColK]], self::groupRow($item, $byCols))) : array_replace_recursive($newData[$item[$currColK]], self::groupRow($item, $byCols));
                }

                $postClosure = false;
                foreach ($byCols as $key => $item) {
                    if ($item['postClosure'])
                        $postClosure = true;
                    else
                        unset($byCols[$key]);
                }

                if ($postClosure) {
                    foreach ($newData as $key => $item) {
                        $newData[$key] = self::doClosure($item, $byCols);
                    }
                }

                if (isset($tableInfo['group']['postClosure'])) {
                    $newData = call_user_func($tableInfo['group']['postClosure'], $newData);
                    unset($tableInfo['group']['postClosure']);
                }

                if ($tableInfo)
                    $newData['tableInfo'] = $tableInfo;

                return $newData;
            }
        }
        return $data;
    }

    private static function groupRow($data, $byCols) {
        if (is_array($data) && is_array($byCols)) {
            $newData = array();

            reset($byCols);
            $prevColK = key($byCols);
            $tableInfo = (is_array($byCols[$prevColK])) ? $byCols[$prevColK]['tableInfo'] : null;
            if ($byCols[$prevColK]['closure'])
                $data = call_user_func_array($byCols[$prevColK]['closure'], array($data));
            unset($byCols[$prevColK]);

            foreach ($data as $key => $item) {
                if ($key != $prevColK)
                    $newData[$key] = "";
                else {
                    $newData[$key] = $item;
                    unset($data[$key]);
                    break;
                }
            }

            reset($byCols);
            $currColK = key($byCols);

            $subRows = array();
            $toSub = false;
            foreach ($data as $key => $item) {
                if (!$toSub && !key_exists($key, $byCols))
                    $newData[$key] = $item;
                else {
                    if (key_exists($key, $byCols)) {
                        $toSub = (is_array($byCols[$key])) ? ( (is_int($byCols[$key]['v'])) ? $byCols[$key]['v'] : sizeof($data) ) : ( (is_int($byCols[$key])) ? $byCols[$key] : sizeof($data) );

                        if ($key == $currColK)
                            $newData[$key . 's'] = array();
                    } else
                        $toSub--;

                    $subRows[$key] = $item;
                }
            }

            if ($currColK)
                $newData[$currColK . 's'][$data[$currColK]] = self::groupRow($subRows, $byCols);

            if ($tableInfo)
                $newData['tableInfo'] = $tableInfo;

            return $newData;
        }
        return null;
    }

    private static function doClosure($data, $byCols) {
        if (is_array($data) && is_array($byCols)) {
            $newData = array();

            $tableInfo = $data['tableInfo'];
            unset($data['tableInfo']);

            $needClosure = false;
            foreach ($data as $key => $item) {
                if (is_array($item))
                    foreach ($item as $subKey => $subItem)
                        $data[$key][$subKey] = self::doClosure($subItem, $byCols);
                elseif (key_exists($key, $byCols))
                    $needClosure = $key;
            }

            $newData = $data;
            if ($needClosure)
                $newData = call_user_func_array($byCols[$needClosure]['postClosure'], array($newData));

            if ($tableInfo)
                $newData['tableInfo'] = $tableInfo;

            return $newData;
        }
        return null;
    }

    public static function ungroupArray($data) {
        if (is_array($data)) {
            $newData = array();

            $tableInfo = $data['tableInfo'];
            unset($data['tableInfo']);

            foreach ($data as $item) {
                self::$ungroupedRows = array();
                self::ungroupRow($item);

                $newData = array_merge($newData, self::$ungroupedRows);
            }

            $newData['tableInfo'] = $tableInfo;

            self::$ungroupedRows = null;

            return $newData;
        }

        return null;
    }

    private static function ungroupRow($data) {
        if (is_array($data)) {
            $newData = array();

            $tableInfo = $data['tableInfo'];
            unset($data['tableInfo']);

            $subRow = array_values(array_filter($data, function($v) {
                        return is_array($v);
                    }));
            $subRow = $subRow[0];

            $data = array_filter(array_map(function ($v) {
                        if (is_array($v))
                            return null;
                        else {
                            if (!$v && !is_int($v))
                                $v = " ";
                            return $v;
                        }
                    }, $data));

            $data['tableInfo'] = $tableInfo;

            if ($subRow) {
                $withoutRowspan = array_filter($data['tableInfo']['cols'], function ($v) {
                    return ($v['rowspan'] === false);
                });

                if ($withoutRowspan) {
                    self::$ungroupedRows[] = $data;

                    foreach ($withoutRowspan as $k => $v)
                        unset($data[$k]);
                }

                foreach ($subRow as $item) {
                    self::ungroupRow(array_replace_recursive($data, $item));
                }
            } else {
                self::$ungroupedRows[] = $data;
            }
        }
    }

    public static function saveToExcel($data = false) {
        if ($data) {
            include("{$_SERVER['DOCUMENT_ROOT']}/admin/PHPExcel/Classes/PHPExcel.php");

            $colKeys = false;
            $titles = false;

            $file = "table.{$_SESSION['auth']['login']}.xlsx";

            $template = "{$_SERVER['DOCUMENT_ROOT']}/lib/class.table.php/empty.xlsx";
            $xls = PHPExcel_IOFactory::load($template);
            self::$sheet = $xls->getActiveSheet();

            //***************************************
            self::$sheet->setSelectedCellByColumnAndRow(0, 1);

//			self::$sheet->setCellValueByColumnAndRow(8, 2, "VALUE");
//			self::$sheet->mergeCellsByColumnAndRow(8, 2, 8, 3);
//			pre(self::$sheet->getMergeCells());

            if ($data['tableInfo']) {
                //Following work will be happening with global variable $info
                self::$info = $data['tableInfo'];
                unset($data['tableInfo']);

                //Collecting information about columns(titles and column keys)
                if (self::$info['excelCols']) {
                    $colKeys = array_keys(self::$info['excelCols']);
                    $titles = array_values(array_filter(self::$info['excelCols']));
                    unset(self::$info['excelCols']);
                } elseif (self::$info['cols']) {
                    $colKeys = array_keys(self::$info['cols']);
                    $titles = array_values(array_filter(self::$info['cols']));
                    unset(self::$info['cols']);
                }
            }
            //Height of each row will be calculated automatically by the default
            if (!isset(self::$info['rowspan']))
                self::$info['rowspan'] = true;

            self::excelGetActiveCellCoord();

            if (is_array($titles) && sizeof($titles)) {
                $titles['tableInfo']['class'] = "head";
                self::excelWriteRow($titles, false);
            }

            foreach ($data as $item) {
                self::excelWriteRow($item, $colKeys);
            }

            //***************************************

            $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
            $objWriter->save("{$_SERVER['DOCUMENT_ROOT']}/tmp/{$file}");

            self::$info = null;
            self::$sheet = null;
            self::$acc = null;

            return $file;
        }
    }

    private static function excelWriteRow($data, $colKeys) {
        if (is_array($data)) {
            $rowRules = false;
            $cellsRules = false;
            //====================
            $rowspan = self::$info['rowspan'];
            $countRows = false;
            $args = "";
            //====================
            $needNewRow = false;
            $subRow = false;

            if ($data['tableInfo']) {
                $rowRules = $data['tableInfo'];
                unset($data['tableInfo']);

                if ($rowRules['excelKeys']) {
                    $rowRules['keys'] = $rowRules['excelKeys'];
                    unset($rowRules['excelKeys']);
                }
                if ($rowRules['cols']) {
                    $cellsRules = $rowRules['cols'];
                    unset($rowRules['cols']);
                }

                if ($rowRules['rowspan']) {
                    $rowspan = $rowRules['rowspan'];
                    unset($rowRules['rowspan']);
                }

                $colKeys = self::modifyKeys($colKeys, $rowRules['keys']);
            }

            $subRow = array_values(array_filter($data, function($v) {
                        return is_array($v);
                    }));
            $subRow = $subRow[0];

            //If do not specify column keys then it is filled with all the elements of the array, except for sub-arrays(all cells)
            if (!$colKeys)
                $colKeys = array_keys(array_filter($data, function($v) {
                            return !is_array($v);
                        }));

            foreach ($colKeys as $i => $key) {
                if (
                        isset($data[$key]) && //$subRow &&
                        (
                        (!isset($cellsRules[$key]['rowspan']) && $rowspan) || (is_bool($cellsRules[$key]['rowspan']) && $cellsRules[$key]['rowspan'])
                        )
                ) {
                    if (!$countRows)
                        $countRows = (is_int($rowspan)) ? $rowspan : (self::countRows($data, true) + 1 * ((bool) array_filter($cellsRules, function($v) {
                                    return $v['rowspan'] === false;
                                })));
                    //: (self::countRows($data, true) - 1*(!(bool)array_filter($cellsRules, function($v){return $v['rowspan'] == false;})));
                    if (!is_int($cellsRules[$key]['rowspan']))
                        $cellsRules[$key]['rowspan'] = $countRows;
                }
                if (isset($data[$key]))
                    if ($rowRules['class'])
                        $cellsRules[$key]['class'] = $rowRules['class'];
                if ($cellsRules[$key]['rowspan'] === false)
                    $needNewRow = true;

                if (isset($data[$key])) {
                    unset($colKeys[$i]);
                    self::excelCellSetValue($i, $data[$key], $cellsRules[$key]);
                }
            }

            if ($subRow) {
                if ($needNewRow)
                    self::$acc['row']++;

                foreach ($subRow as $row)
                    self::excelWriteRow($row, $colKeys);
            } else
                self::$acc['row']++;
        }
    }

    private static function excelGetActiveCellCoord() {
        $activCell = preg_split("/^([A-Z]+)(\d+)$/", self::$sheet->getActiveCell(), null, PREG_SPLIT_DELIM_CAPTURE);
        self::$acc['col'] = (PHPExcel_Cell::columnIndexFromString($activCell[1]) - 1);
        self::$acc['row'] = $activCell[2];
    }

    private static function excelCellSetValue($col, $value, $rules) {
//		$col = 0;

        if ($value === null)
            $value = " ";
        $value = mb_convert_encoding($value, 'utf-8', 'cp1251');

        while (!self::excelCellIsEmpty($col, self::$acc['row']))
            $col++;

        self::$sheet->setCellValueByColumnAndRow($col, self::$acc['row'], $value);

        if ($rules['rowspan'] > 0 || $rules['colspan'] > 0) {
            $toRow = self::$acc['row'] + $rules['rowspan'] - (bool) $rules['rowspan'] * 1;
            $toCol = $col + $rules['colspan'] - (bool) $rules['colspan'] * 1;
            self::$sheet->mergeCellsByColumnAndRow($col, self::$acc['row'], $toCol, $toRow);
        }

        if ($rules['class'] && in_array("head", explode(" ", $rules['class'])))
            self::$sheet->getStyleByColumnAndRow($col, self::$acc['row'])->getFont()->setBold(true);
    }

    private static function excelCellIsEmpty($col, $row) {
        $empty = true;

        if (self::$sheet->getCellByColumnAndRow($col, $row)->getValue())
            $empty = false;

        if ($empty) {
            $cell = self::$sheet->getCellByColumnAndRow($col, $row);

            foreach (self::$sheet->getMergeCells() as $cells) {
                if ($cell->isInRange($cells)) {
                    $empty = false;
                    break;
                }
            }
        }

        return $empty;
    }

    public static function getIndexFunc($pos = false) {
        self::$index = 0;

        return function($data) use($pos) {
            //var_dump(self::$index);
            //Using Table:: instead self:: for php 5.3
            Table::$index++;
            $newData = array();

            if (is_int($pos)) {
                $id = array('id' => Table::$index);
                $newData = array_merge(array_slice($data, 0, $pos), $id, array_slice($data, $pos));
            } else {
                $newData = $data;
                $newData['id'] = Table::$index;
            }

            return $newData;
        };
    }

    public function pre($a) {
        echo "<pre style='text-align:left;'>";
        print_r($a);
        echo "</pre>";
    }

}

?>
