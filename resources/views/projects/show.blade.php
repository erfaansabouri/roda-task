@extends('projects.master')
@section('content')
    @push('styles')
        <style>
            #myHeat {
                background: url({{ $project->plan_image_url }}) no-repeat;
                height: 1000px;
            }
        </style>
    @endpush
    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-medium-3-4">

            <h2 id="getstarted">Project {{ $project->name }}</h2>
            <p>
                Consider below instructions and build your projects!
            </p>
            <div class="uk-overflow-container">
                  <table class="uk-table uk-text-nowrap">
                      <thead>
                      <tr>
                          <th>Step</th>
                          <th>Status</th>
                          <th>Description</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr>
                          <td>1</td>
                          <td id="step-1">Pending</td>
                          <td>Choose origin point on the image (0,0).</td>
                      </tr>
                      <tr>
                          <td>2</td>
                          <td id="step-2">Pending</td>
                          <td>Choose initial point of the device on the image ({{ $movements->first()->x }},{{ $movements->first()->y }}).</td>
                      </tr>
                      <tr>
                          <td>3</td>
                          <td id="step-3">Pending</td>
                          <td>Draw movements pattern. <button id="draw-movements-pattern" class="uk-button uk-button-success" type="button">Draw pattern</button></td>
                      </tr>
                      <tr>
                          <td>4</td>
                          <td id="step-4">Pending</td>
                          <td>Heat your map. <img width="25" src="{{ asset('images/icons8-fire.gif') }}" alt=""> <button id="draw-heat-map" class="uk-button uk-button-danger" type="button">Heat map</button></td>
                      </tr>
                      </tbody>
                  </table>
              </div>
        </div>

    </div>

    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-medium-4-4">
            <canvas id="myCanvas"></canvas>
            <hr>
            <div id="myHeat"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#myHeat').hide()
        var distance_in_meter = "{{ $distance_in_meter }}";
        var distance_in_pixel = 1;
        var scale = 1;
        var points = [];
        @foreach($movements as $movement)
        points.push({x: "{{ $movement->x }}", y: "{{ $movement->y }}"})
        @endforeach
        const canvas = document.getElementById('myCanvas');
        const ctx = canvas.getContext('2d');
        let originPoint = null;
        let initialPoint = null;

        const img = new Image();
        img.onload = function () {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
        };
        img.src = "{{ $project->plan_image_url }}";

        canvas.addEventListener('click', function (event) {
            const rectX = event.offsetX;
            const rectY = event.offsetY;
            console.log(rectX, rectY)
            if (originPoint === null) {
                originPoint = {x: rectX, y: rectY};
                var step_1 = $('#step-1')
                step_1.html('<span style="color: green; font-weight: bolder">'+`Done! (${rectX},${rectY})`+'</span>')
                console.log("Origin done")
                drawPoint(originPoint);
            } else if (initialPoint === null) {
                initialPoint = {x: rectX, y: rectY};
                console.log("Initial Done")
                var step_2 = $('#step-2')
                step_2.html('<span style="color: green; font-weight: bolder">'+`Done! (${rectX},${rectY})`+'</span>')
                var draw_movements_pattern_btn = document.getElementById('draw-movements-pattern');
                draw_movements_pattern_btn.disabled = false;
                drawPoint(initialPoint);
                distance_in_pixel = Math.sqrt(Math.pow(initialPoint.x - originPoint.x, 2) + Math.pow(initialPoint.y - originPoint.y, 2));
                console.log(`Distance in pixel: ${distance_in_pixel}px`);
                console.log(`Distance in meter: ${distance_in_meter}m`);
                scale = distance_in_meter / distance_in_pixel;
                console.log("Scale: " + scale);
            } else {

            }
        });

        var draw_movements_pattern_btn = document.getElementById('draw-movements-pattern');
        draw_movements_pattern_btn.addEventListener('click', function (event) {
            $('#myHeat').hide()
            $('#myCanvas').show()
            if(originPoint === null || initialPoint === null) {
                alert('Choose origin point and initial point!');
            }else{
                var pixel_points = [];
                var heat_points = [];
                for (var i = 0; i < points.length; i++) {
                    pixel_points.push(mapPoint(points[i],{x: 0, y: 0}, originPoint,points[0], initialPoint));
                    heat_points.push({x: pixel_points[i].x , y: pixel_points[i].y, value: 1});
                }
                for (var j = 0; j < pixel_points.length - 1; j++) {
                    drawLine(pixel_points[j], pixel_points[j+1])
                }
                var heatmapInstance = h337.create({
                    container: document.getElementById('myHeat')
                });
                heatmapInstance.setData({
                    max: 15,
                    data: heat_points
                });
                draw_movements_pattern_btn.classList.add('uk-button-success');
                draw_movements_pattern_btn.classList.remove('uk-button-primary');
                draw_movements_pattern_btn.textContent = 'Done! ' + pixel_points.length + " line drawn."
                var step_3 = $('#step-3')
                step_3.html('<span style="color: green; font-weight: bolder">'+`Done! <br>[Distance in meter: ${distance_in_meter}]<br>[Distance in pixel: ${distance_in_pixel}] <br>[Scale: ${scale}] `+'</span>')
            }
        });

        var heat_map_btn = document.getElementById('draw-heat-map');
        heat_map_btn.addEventListener('click', function (event) {
            $('#myCanvas').hide()
            $('#myHeat').show()
            if(originPoint === null || initialPoint === null) {
                alert('Choose origin point and initial point!');
            }else{
                var pixel_points = [];
                var heat_points = [];
                for (var i = 0; i < points.length; i++) {
                    pixel_points.push(mapPoint(points[i],{x: 0, y: 0}, originPoint,points[0], initialPoint));
                    heat_points.push({x: pixel_points[i].x , y: pixel_points[i].y, value: 1});
                }
                var heatmapInstance = h337.create({
                    container: document.getElementById('myHeat')
                });
                heatmapInstance.setData({
                    max: 15,
                    data: heat_points
                });
                heat_map_btn.textContent = 'Done! Your map is now on fire.'
                var step_4 = $('#step-4')
                step_4.html('<span style="color: green; font-weight: bolder">'+`Done! `+'</span>')
            }
        });

        function drawPoint(point) {
            ctx.beginPath();
            ctx.arc(point.x, point.y, 1, 0, 2 * Math.PI);
            ctx.fillStyle = 'red';
            ctx.fill();
        }

        function drawLine(pointA, pointB)
        {
            ctx.strokeStyle = 'blue';
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(pointA.x, pointA.y);
            ctx.lineTo(pointB.x, pointB.y);
            ctx.stroke();
        }

        function mapPoint(pointToBeMapped, originPoint, originPointPrim, initialPoint, initialPointPrim) {
            const deltaX = originPointPrim.x - originPoint.x;
            const deltaY = originPointPrim.y - originPoint.y;
            const deltaXprime = initialPointPrim.x - initialPoint.x;
            const deltaYprime = initialPointPrim.y - initialPoint.y;
            const scaleFactorX = deltaXprime / deltaX;
            const scaleFactorY = deltaYprime / deltaY;
            const translateX = initialPointPrim.x - scaleFactorX * initialPoint.x;
            const translateY = initialPointPrim.y - scaleFactorY * initialPoint.y;

            const mappedX = scaleFactorX * pointToBeMapped.x + translateX;
            const mappedY = scaleFactorY * pointToBeMapped.y + translateY;

            return {
                x: mappedX,
                y: mappedY
            };
        }



    </script>
@endpush
