<?php use Carbon\Carbon;
use Illuminate\Support\Facades\Collection;?>
@extends('layouts.app')
@section('title', 'Danh sách sản phẩm')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 class="m-0">Thống kê đơn hàng</h1>
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- LINE CHART -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Đơn hàng</h3>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown" data-offset="-52" aria-expanded="false">
                                        chọn user
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <ul class="dropdown-menu checkbox-menu checkboxUser" role="menu">
                                        <li class="dropdown-item">
                                            <label>
                                                <input type="checkbox" class="chartCheckBox" value="0"> Tổng
                                            </label>
                                        </li>
                                        @foreach($listUser as $user)
                                            <li class="dropdown-item">
                                                <label>
                                                    <input type="checkbox" class="chartCheckBox" value="{{ $user->id }}">{{ $user->userName }}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="card-tools">

                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="chart">
                                    <div class="card-body table-responsive">
                                        <canvas id="myChartCustom" style="width: 100%; min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col (RIGHT) -->
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- LINE CHART -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Thống kê đơn hàng theo VPS</h3>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown" data-offset="-52" aria-expanded="false">
                                        chọn VPS
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <ul class="dropdown-menu checkbox-menu checkboxVps" role="menu">
                                        <li class="dropdown-item">
                                            <label>
                                                <input type="checkbox" class="chartCheckBoxVps" value="0"> Tổng
                                            </label>
                                        </li>
                                        @foreach($listVps as $vps)
                                            <li class="dropdown-item">
                                                <label>
                                                    <input type="checkbox" class="chartCheckBoxVps" value="{{ $vps->id }}">{{ $vps->name }}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="card-tools">

                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="chart">
                                    <div class="card-body table-responsive">
                                        <canvas id="myChartVps" style="width: 100%; min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col (RIGHT) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
@stop


@section('footer')
    <style>
        .dropdown-menu.show {
            display: block;
            max-height: 300px;
            overflow-y: scroll;
        }
    </style>
    <?php
        function addQuotes($item) {
            return "'" . $item . "'";
        }
        $arrUser = $listUser->pluck('userName')->toArray();
        $arrVps = $listVps->pluck('name')->toArray();
//        if (isset($listOrder) && count($listOrder) > 0) {
//            $arrUser = array_unique(array_column($listOrder, 'userName'));
//        }
    ?>

    <!-- ChartJS -->
    <script src="/plugins/chart.js/Chart.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="/dist/js/demo.js"></script>
    <script>
        $(".checkboxUser").on("change", "input[type='checkbox']", function () {
            $(this).closest("li").toggleClass("active", this.checked);
            const cb = $('.chartCheckBox')
            let val = []
            cb.each((i,v)=>{
                if(v.checked){
                    val.push(i)
                }
            })
            const newDataset = []
            for(const value of val){
                newDataset.push(datasetCustom[value])
            }
            myChartCustom.data.datasets = newDataset
            myChartCustom.update();
        });
        $(".checkboxVps").on("change", "input[type='checkbox']", function () {
            $(this).closest("li").toggleClass("active", this.checked);
            const cb = $('.chartCheckBoxVps')
            let val = []
            cb.each((i,v)=>{
                if(v.checked){
                    val.push(i)
                }
            })
            const newDataset = []
            for(const value of val){
                newDataset.push(datasetVps[value])
            }
            myChartVps.data.datasets = newDataset
            myChartVps.update();
        });
        $(document).on("click", ".allow-focus", function (e) {
            e.stopPropagation();
        });
        const ctxCustom = document.getElementById("myChartCustom");
        const ctxVps = document.getElementById("myChartVps");
        var phpVars = [<?php echo implode(', ', array_map('addQuotes',$arrUser ));?>];
        var phpVarsVps = [<?php echo implode(', ', array_map('addQuotes',$arrVps ));?>];
        const DATA_COUNT = 12;
        const labels = [];
        // for (let i = 0; i < DATA_COUNT; ++i) {
        //     labels.push(i.toString());
        // }

        const datasetCustom = [
            {
                label: 'Tổng',
                data: [],
                fill: false,
                borderColor: 'rgb(255, 99, 132)'
            }
        ];
        for (let i = 0; i < phpVars.length; i++) {
            var dataset = {
                label: phpVars[i],
                data: [],
                fill: false,
                borderColor: '#'+Math.random().toString(16).substr(-6)
            }
            datasetCustom.push(dataset)
        }
        const data = {
            labels: labels,
            datasets: datasetCustom,
        };
        const config = {
            type: "line",
            data: data,
            options: {
                responsive: false,
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: "Đơn hàng",
                },
            },
        };
        ///VPS
        const datasetVps = [
            {
                label: 'Tổng',
                data: [],
                fill: false,
                borderColor: 'rgb(255, 99, 132)'
            }
        ];
        for (let i = 0; i < phpVarsVps.length; i++) {
            var dataset = {
                label: phpVarsVps[i],
                data: [],
                fill: false,
                borderColor: '#'+Math.random().toString(16).substr(-6)
            }
            datasetVps.push(dataset)
        }
        const dataVps = {
            labels: labels,
            datasets: datasetVps,
        };
        const configVps = {
            type: "line",
            data: dataVps,
            options: {
                responsive: false,
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: "Đơn hàng",
                },
            },
        };
        const myChartCustom = new Chart(ctxCustom, config);
        const myChartVps = new Chart(ctxVps, configVps);
        <?php
        $arrDate = [];
        $arrDateStr = "";
        for ($index=30; $index>=0;$index--) {
            $date = Carbon::now()->addDays(-$index)->format('Y-m-d');
            $count = 0;
            if(isset($listOrder)&& count($listOrder)>0)
            {
                $listOrderByDate = $listOrder->filter(function ($item) use ($date) {
                    return $item['created_at'] === $date;
                });
                $count =count($listOrderByDate);
            }

            $arrDateStr =$arrDateStr.'"'.$date.'"'.($index==0?"":",");
            $obj = (object) ['date' => $date, 'count' => $count];
            array_push($arrDate, $obj);
        }
        //$listUser
        ?>
        var response = {"dataTotal":"[<?php
        for ($index=0; $index< count($arrDate);$index++)
        {
            echo( '{\"date\":\"'.$arrDate[$index]->date.'\",\"value\":'.$arrDate[$index]->count.'}'.($index==count($arrDate)-1?"":","));
        } ?>]",
        "dataItem":[<?php
            for ($index=0; $index< count($arrDate);$index++)
            {
                echo( '{"date":"'.$arrDate[$index]->date.'","value":'.$arrDate[$index]->count.'}'.($index==count($arrDate)-1?"":","));
            } ?>],
        "arrX":[<?php for ($index=0; $index< count($arrDate);$index++)
        {
            echo( '"'.$arrDate[$index]->date.'"'.($index==count($arrDate)-1?"":","));
        } ?>],
        "arrY":[<?php for ($index=0; $index< count($arrDate);$index++)
        {
            echo( $arrDate[$index]->count.($index==count($arrDate)-1?"":","));
        } ?>],
        "arrUserX":[<?php
            for ($index=0; $index< count($listUser);$index++)
            {
                echo( '['.$arrDateStr.']'.($index==count($listUser)-1?"":","));
            } ?>],
        "arrUserY":[<?php
            $intdexUser = 0;
            foreach ($listUser as $user) {
                $userId = $user->id;
                $strValue = "[";
                for ($indexDay=30; $indexDay>=0;$indexDay--) {
                    $date = Carbon::now()->addDays(-$indexDay)->format('Y-m-d');

                    $count = 0;
                    if(isset($listOrder)&& count($listOrder)>0)
                    {
                        $listOrderByDate = $listOrder->filter(function ($item) use ($date,$userId) {
                            return $item['created_at'] === $date && $item['userId']==$userId;
                        });
                        $count =count($listOrderByDate);
                    }
                    $strValue =$strValue.$count.($indexDay==0?"":",");
                }
                echo( $strValue.']'.($intdexUser==count($listUser)-1?"":","));
                $intdexUser++;
                // Code xử lý thêm ở đây
            }?>],
            //-------------------------VPS----------------------------------------------
            "arrVpsX":[<?php
                for ($index=0; $index< count($listVps);$index++)
                {
                    echo( '['.$arrDateStr.']'.($index==count($listVps)-1?"":","));
                } ?>],
            "arrVpsY":[<?php
                $intdexVps = 0;
                foreach ($listVps as $vps) {
                    $vpsId = $vps->id;
                    $strValue = "[";
                    for ($indexDay=30; $indexDay>=0;$indexDay--) {
                        $date = Carbon::now()->addDays(-$indexDay)->format('Y-m-d');
                        $count = 0;
                        if(isset($listOrder)&& count($listOrder)>0)
                        {
                            $listOrderByDate = $listOrder->filter(function ($item) use ($date,$vpsId) {
                                return $item['created_at'] === $date && $item['vpsId']==$vpsId;
                            });
                            $count =count($listOrderByDate);
                        }
                        $strValue =$strValue.$count.($indexDay==0?"":",");
                    }
                    echo( $strValue.']'.($intdexVps==count($listVps)-1?"":","));
                    $intdexVps++;
                    // Code xử lý thêm ở đây
                }?>]
        };
        var updateChart = function() {

            myChartCustom.data.labels = response.arrX;
            myChartCustom.data.datasets[0].data = response.arrY;
            for (let i = 0; i < response.arrUserY.length; i++) {
                myChartCustom.data.datasets[i+1].data = response.arrUserY[i];
            }
            myChartCustom.update();

            myChartVps.data.labels = response.arrX;
            myChartVps.data.datasets[0].data = response.arrY;
            for (let i = 0; i < response.arrVpsY.length; i++) {
                myChartVps.data.datasets[i+1].data = response.arrVpsY[i];
            }
            myChartVps.update();
        }

        updateChart();
    </script>

@endsection
