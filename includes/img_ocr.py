import pytesseract
import cv2
import numpy as np
from PIL import Image

# Set the path to Tesseract executable (Only needed for Windows)
pytesseract.pytesseract.tesseract_cmd = r"C:\Users\KEN\AppData\Local\Programs\Tesseract-OCR\tesseract.exe"

# Open an image
img = Image.open(r"drawing_storage\Untitled.png")  # Add 'r' before the string
img = img.convert("L")  # Convert to grayscale

image = cv2.imread(r"drawing_storage\Untitled.png")
denoised = cv2.fastNlMeansDenoisingColored(image, None, 10, 10, 7, 21)
text1 = pytesseract.image_to_string(denoised)

print(text1)

# Extract text
text = pytesseract.image_to_string(img)

# Print extracted text
print(text)

import easyocr

reader = easyocr.Reader(['en'])  # Load English model
result = reader.readtext(r"drawing_storage\Untitled.png") 
print(result)  # Returns detected text