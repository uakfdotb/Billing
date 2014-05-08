function processFilter(filter) {
    var camanCache = {};
    camanCache[filter] = Caman(canvas.toDataURL("image/png"), '#mycanvas', function () { });
    camanCache[filter].revert(function () {
        camanCache[filter][filter](1);
        camanCache[filter].render(function () {
            previewImage();
        });
    });
}

function processSharpen(img, sharpenAmount) {
    var imageData = context.getImageData(0, 0, canvas.width, canvas.height);

    var matrix = [
						-1, -1, -1,
						-1, 9, -1,
						-1, -1, -1
					];
    imageData = applyMatrix(img, imageData, matrix, sharpenAmount);

    context.putImageData(imageData, 0, 0);
}

function processEmboss(img, embossAmount) {
    var imageData = context.getImageData(0, 0, canvas.width, canvas.height);

    var matrix = [
						-2, -1, 0,
						-1, 1, 1,
						0, 1, 2
					];
    imageData = applyMatrix(img, imageData, matrix, embossAmount);

    context.putImageData(imageData, 0, 0);
}

// apply a convolution matrix
function applyMatrix(img, imageData, matrix, amount) {

    // create a second buffer to hold matrix results
    var buffer2 = document.createElement("canvas");
    // get the canvas context 
    var c2 = buffer2.getContext('2d');

    // set the dimensions
    c2.width = buffer2.width = canvas.width;
    c2.height = buffer2.height = canvas.height;

    // draw the image to the new buffer
    c2.drawImage(img, 0, 0, img.width, img.height);
    var bufferedPixels = c2.getImageData(0, 0, img.width, img.height);

    // speed up access
    var data = imageData.data, bufferedData = bufferedPixels.data, imgWidth = img.width;

    // make sure the matrix adds up to 1
    /* 		matrix = normalizeMatrix(matrix); */

    // calculate the dimensions, just in case this ever expands to 5 and beyond
    var matrixSize = Math.sqrt(matrix.length);

    // loop through every pixel
    for (var i = 1; i < imgWidth - 1; i++) {
        for (var j = 1; j < img.height - 1; j++) {

            // temporary holders for matrix results
            var sumR = sumG = sumB = 0;

            // loop through the matrix itself
            for (var h = 0; h < matrixSize; h++) {
                for (var w = 0; w < matrixSize; w++) {

                    // get a refence to a pixel position in the matrix
                    var r = convertCoordinates(i + h - 1, j + w - 1, imgWidth) << 2;

                    // find RGB values for that pixel
                    var currentPixel = {
                        r: bufferedData[r],
                        g: bufferedData[r + 1],
                        b: bufferedData[r + 2]
                    };

                    // apply the value from the current matrix position
                    sumR += currentPixel.r * matrix[w + h * matrixSize];
                    sumG += currentPixel.g * matrix[w + h * matrixSize];
                    sumB += currentPixel.b * matrix[w + h * matrixSize];
                }
            }

            // get a reference for the final pixel
            var ref = convertCoordinates(i, j, imgWidth) << 2;
            var thisPixel = {
                r: data[ref],
                g: data[ref + 1],
                b: data[ref + 2]
            };

            // finally, apply the adjusted values
            data = setRGB(data, ref,
					findColorDifference(amount, sumR, thisPixel.r),
					findColorDifference(amount, sumG, thisPixel.g),
					findColorDifference(amount, sumB, thisPixel.b));
        }
    }

    // code to clean the secondary buffer out of the DOM would be good here

    return (imageData);
}
// convert x/y coordinates to pixel index reference
function convertCoordinates(x, y, w) {
    return x + (y * w);
}
// find a specified distance between two colours
function findColorDifference(dif, dest, src) {
    return (dif * dest + (1 - dif) * src);
}
// throw three new RGB values into the pixels object at a specific spot
function setRGB(data, index, r, g, b) {
    data[index] = r;
    data[index + 1] = g;
    data[index + 2] = b;
    return data;
}

// calculate gaussian blur
// adapted from http://pvnick.blogspot.com/2010/01/im-currently-porting-image-segmentation.html
function processGaussianBlur(amount) {
    var imageData = context.getImageData(0, 0, canvas.width, canvas.height);

    var width = canvas.width;
    var width4 = width << 2;
    var height = canvas.height;

    var data = imageData.data;

    // compute coefficients as a function of amount
    var q;
    if (amount < 0.0) {
        amount = 0.0;
    }
    if (amount >= 2.5) {
        q = 0.98711 * amount - 0.96330;
    } else if (amount >= 0.5) {
        q = 3.97156 - 4.14554 * Math.sqrt(1.0 - 0.26891 * amount);
    } else {
        q = 2 * amount * (3.97156 - 4.14554 * Math.sqrt(1.0 - 0.26891 * 0.5));
    }

    //compute b0, b1, b2, and b3
    var qq = q * q;
    var qqq = qq * q;
    var b0 = 1.57825 + (2.44413 * q) + (1.4281 * qq) + (0.422205 * qqq);
    var b1 = ((2.44413 * q) + (2.85619 * qq) + (1.26661 * qqq)) / b0;
    var b2 = (-((1.4281 * qq) + (1.26661 * qqq))) / b0;
    var b3 = (0.422205 * qqq) / b0;
    var bigB = 1.0 - (b1 + b2 + b3);

    // horizontal
    for (var c = 0; c < 3; c++) {
        for (var y = 0; y < height; y++) {
            // forward 
            var index = y * width4 + c;
            var indexLast = y * width4 + ((width - 1) << 2) + c;
            var pixel = data[index];
            var ppixel = pixel;
            var pppixel = ppixel;
            var ppppixel = pppixel;
            for (; index <= indexLast; index += 4) {
                pixel = bigB * data[index] + b1 * ppixel + b2 * pppixel + b3 * ppppixel;
                data[index] = pixel;
                ppppixel = pppixel;
                pppixel = ppixel;
                ppixel = pixel;
            }
            // backward
            index = y * width4 + ((width - 1) << 2) + c;
            indexLast = y * width4 + c;
            pixel = data[index];
            ppixel = pixel;
            pppixel = ppixel;
            ppppixel = pppixel;
            for (; index >= indexLast; index -= 4) {
                pixel = bigB * data[index] + b1 * ppixel + b2 * pppixel + b3 * ppppixel;
                data[index] = pixel;
                ppppixel = pppixel;
                pppixel = ppixel;
                ppixel = pixel;
            }
        }
    }

    // vertical
    for (var c = 0; c < 3; c++) {
        for (var x = 0; x < width; x++) {
            // forward 
            var index = (x << 2) + c;
            var indexLast = (height - 1) * width4 + (x << 2) + c;
            var pixel = data[index];
            var ppixel = pixel;
            var pppixel = ppixel;
            var ppppixel = pppixel;
            for (; index <= indexLast; index += width4) {
                pixel = bigB * data[index] + b1 * ppixel + b2 * pppixel + b3 * ppppixel;
                data[index] = pixel;
                ppppixel = pppixel;
                pppixel = ppixel;
                ppixel = pixel;
            }
            // backward
            index = (height - 1) * width4 + (x << 2) + c;
            indexLast = (x << 2) + c;
            pixel = data[index];
            ppixel = pixel;
            pppixel = ppixel;
            ppppixel = pppixel;
            for (; index >= indexLast; index -= width4) {
                pixel = bigB * data[index] + b1 * ppixel + b2 * pppixel + b3 * ppppixel;
                data[index] = pixel;
                ppppixel = pppixel;
                pppixel = ppixel;
                ppixel = pixel;
            }
        }
    }

    context.putImageData(imageData, 0, 0);
}

function processVintage(bVignette) {
    var noise = 100;
    var r = [0, 0, 0, 1, 1, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 7, 7, 7, 7, 8, 8, 8, 9, 9, 9, 9, 10, 10, 10, 10, 11, 11, 12, 12, 12, 12, 13, 13, 13, 14, 14, 15, 15, 16, 16, 17, 17, 17, 18, 19, 19, 20, 21, 22, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 39, 40, 41, 42, 44, 45, 47, 48, 49, 52, 54, 55, 57, 59, 60, 62, 65, 67, 69, 70, 72, 74, 77, 79, 81, 83, 86, 88, 90, 92, 94, 97, 99, 101, 103, 107, 109, 111, 112, 116, 118, 120, 124, 126, 127, 129, 133, 135, 136, 140, 142, 143, 145, 149, 150, 152, 155, 157, 159, 162, 163, 165, 167, 170, 171, 173, 176, 177, 178, 180, 183, 184, 185, 188, 189, 190, 192, 194, 195, 196, 198, 200, 201, 202, 203, 204, 206, 207, 208, 209, 211, 212, 213, 214, 215, 216, 218, 219, 219, 220, 221, 222, 223, 224, 225, 226, 227, 227, 228, 229, 229, 230, 231, 232, 232, 233, 234, 234, 235, 236, 236, 237, 238, 238, 239, 239, 240, 241, 241, 242, 242, 243, 244, 244, 245, 245, 245, 246, 247, 247, 248, 248, 249, 249, 249, 250, 251, 251, 252, 252, 252, 253, 254, 254, 254, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255],
            g = [0, 0, 1, 2, 2, 3, 5, 5, 6, 7, 8, 8, 10, 11, 11, 12, 13, 15, 15, 16, 17, 18, 18, 19, 21, 22, 22, 23, 24, 26, 26, 27, 28, 29, 31, 31, 32, 33, 34, 35, 35, 37, 38, 39, 40, 41, 43, 44, 44, 45, 46, 47, 48, 50, 51, 52, 53, 54, 56, 57, 58, 59, 60, 61, 63, 64, 65, 66, 67, 68, 69, 71, 72, 73, 74, 75, 76, 77, 79, 80, 81, 83, 84, 85, 86, 88, 89, 90, 92, 93, 94, 95, 96, 97, 100, 101, 102, 103, 105, 106, 107, 108, 109, 111, 113, 114, 115, 117, 118, 119, 120, 122, 123, 124, 126, 127, 128, 129, 131, 132, 133, 135, 136, 137, 138, 140, 141, 142, 144, 145, 146, 148, 149, 150, 151, 153, 154, 155, 157, 158, 159, 160, 162, 163, 164, 166, 167, 168, 169, 171, 172, 173, 174, 175, 176, 177, 178, 179, 181, 182, 183, 184, 186, 186, 187, 188, 189, 190, 192, 193, 194, 195, 195, 196, 197, 199, 200, 201, 202, 202, 203, 204, 205, 206, 207, 208, 208, 209, 210, 211, 212, 213, 214, 214, 215, 216, 217, 218, 219, 219, 220, 221, 222, 223, 223, 224, 225, 226, 226, 227, 228, 228, 229, 230, 231, 232, 232, 232, 233, 234, 235, 235, 236, 236, 237, 238, 238, 239, 239, 240, 240, 241, 242, 242, 242, 243, 244, 245, 245, 246, 246, 247, 247, 248, 249, 249, 249, 250, 251, 251, 252, 252, 252, 253, 254, 255],
            b = [53, 53, 53, 54, 54, 54, 55, 55, 55, 56, 57, 57, 57, 58, 58, 58, 59, 59, 59, 60, 61, 61, 61, 62, 62, 63, 63, 63, 64, 65, 65, 65, 66, 66, 67, 67, 67, 68, 69, 69, 69, 70, 70, 71, 71, 72, 73, 73, 73, 74, 74, 75, 75, 76, 77, 77, 78, 78, 79, 79, 80, 81, 81, 82, 82, 83, 83, 84, 85, 85, 86, 86, 87, 87, 88, 89, 89, 90, 90, 91, 91, 93, 93, 94, 94, 95, 95, 96, 97, 98, 98, 99, 99, 100, 101, 102, 102, 103, 104, 105, 105, 106, 106, 107, 108, 109, 109, 110, 111, 111, 112, 113, 114, 114, 115, 116, 117, 117, 118, 119, 119, 121, 121, 122, 122, 123, 124, 125, 126, 126, 127, 128, 129, 129, 130, 131, 132, 132, 133, 134, 134, 135, 136, 137, 137, 138, 139, 140, 140, 141, 142, 142, 143, 144, 145, 145, 146, 146, 148, 148, 149, 149, 150, 151, 152, 152, 153, 153, 154, 155, 156, 156, 157, 157, 158, 159, 160, 160, 161, 161, 162, 162, 163, 164, 164, 165, 165, 166, 166, 167, 168, 168, 169, 169, 170, 170, 171, 172, 172, 173, 173, 174, 174, 175, 176, 176, 177, 177, 177, 178, 178, 179, 180, 180, 181, 181, 181, 182, 182, 183, 184, 184, 184, 185, 185, 186, 186, 186, 187, 188, 188, 188, 189, 189, 189, 190, 190, 191, 191, 192, 192, 193, 193, 193, 194, 194, 194, 195, 196, 196, 196, 197, 197, 197, 198, 199];

    var imageData = context.getImageData(0, 0, canvas.width, canvas.height);

    for (var i = 0; i < imageData.data.length; i += 4) {
        // change colors
        imageData.data[i] = r[imageData.data[i]];
        imageData.data[i + 1] = g[imageData.data[i + 1]];
        imageData.data[i + 2] = b[imageData.data[i + 2]];

        if (noise > 0) {
            var noise = Math.round(noise - Math.random() * noise / 2);

            var dblHlp = 0;
            for (var k = 0; k < 3; k++) {
                dblHlp = noise + imageData.data[i + k];
                imageData.data[i + k] = ((dblHlp > 255) ? 255 : ((dblHlp < 0) ? 0 : dblHlp));
            }
        }
    }

    context.putImageData(imageData, 0, 0);

    // Vignette
    if (bVignette) processVignette(0.5, 0.1);
};


function processGrayscale(bVignette) {
    var p1 = 0.3;
    var p2 = 0.59;
    var p3 = 0.11;
    var er = 0;
    var eg = 0;
    var eb = 0;

    var imageData = context.getImageData(0, 0, canvas.width, canvas.height);

    var data = imageData.data;
    for (var i = 0, n = data.length; i < n; i += 4) {
        var grayscale = data[i] * p1 + data[i + 1] * p2 + data[i + 2] * p3;
        data[i] = grayscale + er;
        data[i + 1] = grayscale + eg;
        data[i + 2] = grayscale + eb;
    }

    context.putImageData(imageData, 0, 0);

    // Vignette
    if (bVignette) processVignette(0.7, 0.1);
}

function processVignette(vignetteBlack, vignetteWhite) {
    var gradient;
    var outerRadius = Math.sqrt(Math.pow(canvas.width / 2, 2) + Math.pow(canvas.height / 2, 2));
    context.globalCompositeOperation = 'source-over';
    gradient = context.createRadialGradient(canvas.width / 2, canvas.height / 2, 0, canvas.width / 2, canvas.height / 2, outerRadius);
    gradient.addColorStop(0, 'rgba(0,0,0,0)');
    gradient.addColorStop(0.45, 'rgba(0,0,0,0)');
    gradient.addColorStop(1, 'rgba(0,0,0,' + vignetteBlack + ')');
    context.fillStyle = gradient;
    context.fillRect(0, 0, canvas.width, canvas.height);

    context.globalCompositeOperation = 'lighter';
    gradient = context.createRadialGradient(canvas.width / 2, canvas.height / 2, 0, canvas.width / 2, canvas.height / 2, outerRadius);
    gradient.addColorStop(0, 'rgba(255,255,255,' + vignetteWhite + ')');
    gradient.addColorStop(0.45, 'rgba(255,255,255,0)');
    gradient.addColorStop(1, 'rgba(0,0,0,0)');
    context.fillStyle = gradient;
    context.fillRect(0, 0, canvas.width, canvas.height);
}