import os
import time

def sleep(secs):
    time.sleep(secs)

def fls_dir():
    return os.path.dirname(os.path.abspath(__file__))

def scandir(folder):
    files = os.listdir(folder)
    files = [f for f in files if os.path.isfile(os.path.join(folder, f))]
    files.sort()
    return files

def rand(min,max):
    import random
    return random.randint(min,max)

assets_dir = ''

def set_assets_dir(dir):
    global assets_dir
    assets_dir = dir

def append_assets_dir(f):
    if assets_dir:
        return assets_dir + '/' + f
    return f

def locate_and_click(img):
    import pyautogui
    image_location = pyautogui.locateOnScreen(append_assets_dir(img),.8)
    image_center = pyautogui.center(image_location)
    pyautogui.click(image_center)
