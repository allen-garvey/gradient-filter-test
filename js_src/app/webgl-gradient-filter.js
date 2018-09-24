
import WebGl from './webgl.js';
import Shader from './webgl-shader.js';

function createFilterFunc(gl){
    const fragmentShaderText = Shader.shaderText('webgl-fragment-gradient-filter').replace('#{{hsvFunctions}}', Shader.shaderText('webgl-hsv-functions')).replace('#{{hslFunctions}}', Shader.shaderText('webgl-hsl-functions'));
    const drawFunc = WebGl.createDrawImageFunc(gl, Shader.vertexShaderText, fragmentShaderText, ['u_gradient_points_array']);
    
    return function(gl, tex, texWidth, texHeight, numPoints){
        const pointsArray = new Float32Array(numPoints * 3);

        for(let i=0;i<pointsArray.length;i+=3){
            //webgl coordinates are between 0-1
            const x = Math.random();
            const y = Math.random();
            //hue in webgl between 0-1
            const hue = Math.random();
            pointsArray[i] = x;
            pointsArray[i+1] = y;
            pointsArray[i+2] = hue;
        }

        drawFunc(gl, tex, texWidth, texHeight, (gl, customUniformLocations)=>{
            //initialize uniforms
            gl.uniform3fv(customUniformLocations['u_gradient_points_array'], pointsArray);
        });
    };
}

let filterFunc = null;

function filter(gl, texture, imageWidth, imageHeight, numPoints){
    filterFunc = filterFunc || createFilterFunc(gl);
    // Tell WebGL how to convert from clip space to pixels
    gl.viewport(0, 0, gl.canvas.width, gl.canvas.height);
    filterFunc(gl, texture, imageWidth, imageHeight, numPoints);
}



export default {
    filter,
};