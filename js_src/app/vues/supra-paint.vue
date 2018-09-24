<template>
    <div>
        <button class="btn btn-primary" @click="openDeviceImage" title="Open local image file">Image file</button>
        <canvas ref="sourceCanvas"></canvas>
        <canvas ref="webglCanvas"></canvas>
    </div>
</template>

<script>
import Canvas from '../canvas.js';
import Fs from '../fs.js';
import GradientFilter from '../webgl-gradient-filter.js';
import Constants from '../../generated_output/app/constants.js';
import WebGl from '../webgl.js';

let fileInput;
let webglCanvas;
let sourceCanvas;
let sourceWebglTexture;


export default {
    name: 'supra-paint',
    created(){
        
    },
    mounted(){
        webglCanvas = Canvas.createWebgl(this.$refs.webglCanvas);
        sourceCanvas = Canvas.create(this.$refs.sourceCanvas);
    },
    data(){
        return {
            loadedImage: null,
        };
    },
    computed: {
        
    },
    watch: {
        loadedImage(newValue, oldValue){

        },
    },
    methods: {
        openDeviceImage(){
            if(!fileInput){
                fileInput = document.createElement('input');
                fileInput.type = 'file';
                
                fileInput.addEventListener('change', (e)=>{
                    Fs.openImageFile(e, this.loadImage, this.openImageError);
                    fileInput.value = null;
                }, false);
            }
            fileInput.click();
        },
        loadImage(image, file){
            const loadedImage = {
                width: image.width,
                height: image.height,
                fileName: file.name,
                fileSize: file.size,
                fileType: file.type,
            };
            
            //resize large images if necessary
            const largeImageDimensionThreshold = 1200;
            const largestImageDimension = Math.max(loadedImage.width, loadedImage.height);
            if(this.automaticallyResizeLargeImages && largestImageDimension > largeImageDimensionThreshold){
                const resizePercentage = largeImageDimensionThreshold / largestImageDimension;
                Canvas.loadImage(sourceCanvas, image, resizePercentage);
                loadedImage.width = sourceCanvas.canvas.width;
                loadedImage.height = sourceCanvas.canvas.height;
            }
            else{
                Canvas.loadImage(sourceCanvas, image);
            }
            //finish loading image
            this.loadedImage = loadedImage;
            this.filterImage();
        },
        openImageError(errorMessage){
            console.log(errorMessage);
        },
        filterImage(){
            webglCanvas.gl.deleteTexture(sourceWebglTexture);
            sourceWebglTexture = WebGl.createAndLoadTextureFromCanvas(webglCanvas.gl, sourceCanvas.canvas);

            webglCanvas.canvas.width = this.loadedImage.width;
            webglCanvas.canvas.height = this.loadedImage.height;

            GradientFilter.filter(webglCanvas.gl, sourceWebglTexture, this.loadedImage.width, this.loadedImage.height, Constants.NUM_GRADIENT_POINTS);
        },
    },
};
</script>

