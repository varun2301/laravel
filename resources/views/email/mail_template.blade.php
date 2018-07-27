<?php
        //dd($data);
        $content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        
                <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <title></title>
                            <style>td{ padding: 0px; }</style>
                        </head>
                        <body>'.
                            $data['main_content'].'

                            <br><br><br>
                            <table border="1" cellpadding="5" cellspacing="0" height="100%" width="80%">
                                    <tr>
                                        <td colspan="5" style="padding:10px;" style="padding:10px;">Date - <strong>'.$data['start_date'].'</strong> - <strong>'.$data['end_date'].'</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Project Name</th>
                                        <th>Task Name</th>
                                        <th>Planned Time</th>
                                        <th>Log Time</th>
                                    </tr>';
                                    if(count($data['main_data']) > 0)
                                        foreach ($data["main_data"] as $user) {

                                            $content .='<tr>
                                                <td style="padding:10px;">'.(isset($user['employee_name']) ? $user['employee_name'] : "").'</td>
                                                <td style="padding:10px;">'.(isset($user['project_name']) ? $user['project_name'] : "").'</td>
                                                <td style="padding:10px;">'.(isset($user['task_name']) ? $user['task_name'] : "").'</td>
                                                <td style="padding:10px;">'.(isset($user['planned_time']) ? $user['planned_time'] : "").'</td>
                                                <td style="padding:10px;">'.(isset($user['log_time']) ? $user['log_time'] : "").'</td>
                                               
                                                </tr>';
                                        }
                                             
                    $content .='</table> 
                            <br><br><br>
                            </body>
                </html>';
                
        echo $content;