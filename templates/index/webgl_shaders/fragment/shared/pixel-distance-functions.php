<script type="webgl/fragment-shader-function" id="webgl-rgb-distance">
    float quick_distance(vec3 pixel1, vec3 pixel2){
        vec3 distances = pixel1 - pixel2;
        return dot(vec3(1.0), distances * distances);
    }
</script>
<script type="webgl/fragment-shader-function" id="webgl-luma-distance">
    <?php //rgb with correction for luma based on: http://www.tannerhelland.com/3643/grayscale-image-algorithm-vb6/ ?>
    float quick_distance(vec3 pixel1, vec3 pixel2){
        vec3 distances = pixel1 - pixel2;
        return pixel_luma(distances * distances);
    }
</script>
<?php // fraction values of 0.1 and 0.3 seem to work best, but 0.1 is used to differentiate it from hue-lightness distance ?>
<script type="webgl/fragment-shader-function" id="webgl-hue-distance">
    float quick_distance(vec3 pixel1, vec3 pixel2){
        vec3 hsl1 = rgb2hsl(pixel1);
        vec3 hsl2 = rgb2hsl(pixel2);
        float hueDist = hue_distance(hsl1.x, hsl2.x);

        if(hsl1.y < 0.07){
            float fraction = hsl1.y / 0.07;
            vec2 hlDist = vec2(hueDist, hsl1.z - hsl2.z);
            return dot(vec2(8.0 * fraction, (1.0 - fraction)), hlDist*hlDist);
        }
        return hueDist * hueDist;
    }
</script>
<script type="webgl/fragment-shader-function" id="webgl-hue-lightness-distance">
    float quick_distance(vec3 pixel1, vec3 pixel2){
        vec3 hsl1 = rgb2hsl(pixel1);
        vec3 hsl2 = rgb2hsl(pixel2);
        vec2 hlDist = vec2(hue_distance(hsl1.x, hsl2.x), hsl1.z - hsl2.z);

        if(hsl1.y < 0.3){
            float fraction = hsl1.y / 0.3;
            return dot(vec2(2.0 * fraction, (1.0 - fraction)), hlDist*hlDist);
        }
        return dot(vec2(32.0, 1.0), hlDist*hlDist);
    }
</script>
<script type="webgl/fragment-shader-function" id="webgl-lightness-distance">
    float quick_distance(vec3 pixel1, vec3 pixel2){
        float lightness1 = lightness(pixel1);
        float lightness2 = lightness(pixel2);
        return abs(lightness1 - lightness2);
    }
</script>
<script type="webgl/fragment-shader-function" id="webgl-hue-saturation-lightness-distance">
    float quick_distance(vec3 pixel1, vec3 pixel2){
        vec3 hsl1 = rgb2hsl(pixel1);
        vec3 hsl2 = rgb2hsl(pixel2);
        vec3 hslDist = vec3(hue_distance(hsl1.r, hsl2.r), hsl1.gb - hsl2.gb);  
        return dot(vec3(8.0, 1.0, 32.0), hslDist * hslDist);
    }
</script>
<?php //distance for outline filter palette color mode
    //prioritize hue, then lightness, then less saturation,
    //since darker colors should be less saturated
?>
<script type="webgl/fragment-shader-function" id="webgl-hsl2-distance">
    float quick_distance(vec3 pixel1, vec3 pixel2){
        vec3 hsl1 = rgb2hsl(pixel1);
        vec3 hsl2 = rgb2hsl(pixel2);
        float hDist = hue_distance(hsl1.r, hsl2.r);
        vec2 slDist = hsl1.gb - hsl2.gb;  
        vec3 hslDist = vec3(hDist, slDist);

        return dot(vec3(8.0, 1.0, 4.0), hslDist * hslDist);
    }
</script>
<?php //same as hsl2 distance, but finds complementary hue by taking inverse of hue_distance
?>
<script type="webgl/fragment-shader-function" id="webgl-hsl2-complementary-distance">
    float quick_distance(vec3 pixel1, vec3 pixel2){
        vec3 hsl1 = rgb2hsl(pixel1);
        vec3 hsl2 = rgb2hsl(pixel2);
        <?php //maximum hue distance is 0.5, since hue is circular ?>
        float hDist = 0.5 - hue_distance(hsl1.r, hsl2.r);
        vec2 slDist = hsl1.gb - hsl2.gb;  
        vec3 hslDist = vec3(hDist, slDist);

        return dot(vec3(8.0, 1.0, 4.0), hslDist * hslDist);
    }
</script>