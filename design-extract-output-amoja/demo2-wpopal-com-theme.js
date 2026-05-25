// React Theme — extracted from https://demo2.wpopal.com/amoja/
// Compatible with: Chakra UI, Stitches, Vanilla Extract, or any CSS-in-JS

/**
 * TypeScript type definition for this theme:
 *
 * interface Theme {
 *   colors: {
    primary: string;
    secondary: string;
    accent: string;
    background: string;
    foreground: string;
    neutral50: string;
    neutral100: string;
    neutral200: string;
    neutral300: string;
    neutral400: string;
    neutral500: string;
 *   };
 *   fonts: {
    body: string;
 *   };
 *   fontSizes: {
    '25': string;
    '26': string;
    '32': string;
    '36': string;
    '42': string;
    '56': string;
    '60': string;
    '64': string;
    '74': string;
    '80': string;
    '96': string;
    '110': string;
 *   };
 *   space: {
    '1': string;
    '24': string;
    '35': string;
    '50': string;
    '55': string;
    '60': string;
    '65': string;
    '70': string;
    '75': string;
    '80': string;
    '100': string;
    '110': string;
    '120': string;
    '140': string;
    '150': string;
    '192': string;
 *   };
 *   radii: {
    sm: string;
    md: string;
    lg: string;
    full: string;
 *   };
 *   shadows: {
    sm: string;
    xl: string;
 *   };
 *   states: {
 *     hover: { opacity: number };
 *     focus: { opacity: number };
 *     active: { opacity: number };
 *     disabled: { opacity: number };
 *   };
 * }
 */

export const theme = {
  "colors": {
    "primary": "#e49951",
    "secondary": "#153d4b",
    "accent": "#ff0000",
    "background": "#f2f2ec",
    "foreground": "#000000",
    "neutral50": "#ffffff",
    "neutral100": "#000000",
    "neutral200": "#838a8d",
    "neutral300": "#e0dfd8",
    "neutral400": "#eeeeee",
    "neutral500": "#ccd6df"
  },
  "fonts": {
    "body": "'amoja-icon', sans-serif"
  },
  "fontSizes": {
    "25": "25px",
    "26": "26px",
    "32": "32px",
    "36": "36px",
    "42": "42px",
    "56": "56px",
    "60": "60px",
    "64": "64px",
    "74": "74px",
    "80": "80px",
    "96": "96px",
    "110": "110px"
  },
  "space": {
    "1": "1px",
    "24": "24px",
    "35": "35px",
    "50": "50px",
    "55": "55px",
    "60": "60px",
    "65": "65px",
    "70": "70px",
    "75": "75px",
    "80": "80px",
    "100": "100px",
    "110": "110px",
    "120": "120px",
    "140": "140px",
    "150": "150px",
    "192": "192px"
  },
  "radii": {
    "sm": "4px",
    "md": "10px",
    "lg": "15px",
    "full": "50px"
  },
  "shadows": {
    "sm": "rgb(242, 242, 236) 0px 0px 0px 15px",
    "xl": "rgba(0, 0, 0, 0.1) 0px 4px 30px 0px"
  },
  "states": {
    "hover": {
      "opacity": 0.08
    },
    "focus": {
      "opacity": 0.12
    },
    "active": {
      "opacity": 0.16
    },
    "disabled": {
      "opacity": 0.38
    }
  }
};

// MUI v5 theme
export const muiTheme = {
  "palette": {
    "primary": {
      "main": "#e49951",
      "light": "hsl(29, 73%, 76%)",
      "dark": "hsl(29, 73%, 46%)"
    },
    "secondary": {
      "main": "#153d4b",
      "light": "hsl(196, 56%, 34%)",
      "dark": "hsl(196, 56%, 10%)"
    },
    "background": {
      "default": "#f2f2ec",
      "paper": "#ffffff"
    },
    "text": {
      "primary": "#000000",
      "secondary": "#153d4b"
    }
  },
  "typography": {
    "fontFamily": "'amoja-icon', sans-serif",
    "h1": {
      "fontSize": "60px",
      "fontWeight": "400",
      "lineHeight": "60px"
    }
  },
  "shape": {
    "borderRadius": 10
  },
  "shadows": [
    "rgb(242, 242, 236) 0px 0px 0px 15px",
    "rgba(0, 0, 0, 0.1) 0px 4px 30px 0px"
  ]
};

export default theme;
