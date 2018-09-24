<?php 

?>
<script type="webgl/fragment-shader" id="webgl-fragment-gradient-filter">
precision mediump float;

varying vec2 v_texcoord;
uniform sampler2D u_texture;

uniform vec3 u_gradient_points_array[<?= NUM_GRADIENT_POINTS; ?>];

#{{hsvFunctions}}
#{{hslFunctions}}


void main(){
    vec4 pixel = texture2D(u_texture, v_texcoord);
    vec3 adjustedPixel = vec3(0.0);
    
    float pixelHue = 0.0;

    float closestPointDistanceNormalized = 1.0;
    for(int i=0;i<<?= NUM_GRADIENT_POINTS; ?>;i++){
        vec3 currentPoint = u_gradient_points_array[i];
        float currentDistance = distance(currentPoint.xy, v_texcoord.xy);
        <?php //since distances are between 0.0-1.0, largest distance between 2 points is sqrt of 2 ?>
        float normalizedDistance = currentDistance / <?= SQRT_2 ?>;
        closestPointDistanceNormalized = min(closestPointDistanceNormalized, normalizedDistance);
    }

    for(int i=0;i<<?= NUM_GRADIENT_POINTS; ?>;i++){
        vec3 currentPoint = u_gradient_points_array[i];
        float currentDistance = distance(currentPoint.xy, v_texcoord.xy);
        <?php //since distances are between 0.0-1.0, largest distance between 2 points is sqrt of 2 ?>
        float normalizedDistance = currentDistance / <?= SQRT_2 ?>;
        
        float closestDistanceRatio = (normalizedDistance / closestPointDistanceNormalized);
        pixelHue += currentPoint.z * (1.0 / (closestDistanceRatio * closestDistanceRatio * closestDistanceRatio));
    }

    //pixelHue /= float(<?= NUM_GRADIENT_POINTS; ?>);
    pixelHue = mod(pixelHue, 1.0);
    float pixelSaturation = max(1.0 - 4.0 * closestPointDistanceNormalized, 0.0);

    vec3 hsl = rgb2hsl(pixel.rgb);
    adjustedPixel = hsl2rgb(vec3(pixelHue, pixelSaturation, hsl.z));
    
    gl_FragColor = vec4(adjustedPixel, pixel.a);
    
}
</script>