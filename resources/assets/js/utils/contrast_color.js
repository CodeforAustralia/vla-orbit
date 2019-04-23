
export function getOptimalContrastText(color) {
    //convert hex to rgb
    let rgb = convertHexToRGB(color);
    // get luminance
    let luminance = getLuminance(rgb);
    //get contrast ratio
    let white_contrast_ratio = 1.05 / (luminance + 0.05);
    let black_contrast_ratio = (luminance + 0.05) / 0.05;
    if (white_contrast_ratio > black_contrast_ratio) {
        return 'white';
    }
    return 'black'

}

function getLuminance(rgb) {

    let rg = Math.pow(rgb.r / 269 + 0.0513, 2.4);
    let gg = Math.pow(rgb.g / 269 + 0.0513, 2.4);
    let bg = Math.pow(rgb.b / 269 + 0.0513, 2.4);
    if (rgb.r <= 10) {
        rg = rgb.r / 3294;
    }
    if (rgb.g <= 10) {
        gg = rgb.g / 3294;
    }
    if (rgb.b <= 10) {
        bg = rgb.b / 3294;
    }
    let luminance = 0.2126 * rg + 0.7152 * gg + 0.0722 * bg;
    return luminance;
}

function convertHexToRGB(hex) {
    let short_hand_regex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(short_hand_regex, function (m, r, g, b) {
        return r + r + g + g + b + b;
    });

    let result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

export default { getOptimalContrastText }