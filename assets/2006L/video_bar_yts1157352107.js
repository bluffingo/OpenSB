    var imageBrowsers = new Object();
    var images_loaded = false;

    function shiftLeft(bar_id) {
        if (images_loaded == false) {
            return;
        }
        imageBrowsers[bar_id].shiftPageLeft();
        imageBrowsers[bar_id].showImages();
    }
    function shiftRight(bar_id) {
        if (images_loaded == false) {
            return;
        }
        imageBrowsers[bar_id].shiftPageRight();
        imageBrowsers[bar_id].showImages();
    }
    
    // CLASS: ImageBrowser: Stores the array of ytImage and does stuff with them
    function ImageBrowser(display_count, step_size, root_div_id) {
        this.display_count = display_count;
        this.step_size = step_size;
        this.root_div_id = root_div_id;
        this.display_array = new Array();
        
        this.addImage = imageBrowserAddImage;
        this.initDisplay = imageBrowserInitDisplay;

        this.shiftPageLeft = imageBrowserShiftPageLeft;
        this.shiftPageRight = imageBrowserShiftPageRight;

        this.shiftLeft = imageBrowserShiftLeft;
        this.shiftRight = imageBrowserShiftRight;
        this.showImages = imageBrowserShowImages;
        this.prefetchNextPrevImages = imageBrowserPrefetchNextPrevImages;
        this.printPageNumbers = imageBrowserPrintPageNumbers;
		this.incrementIndex = imageBrowserIncrementIndex;
		this.decrementIndex = imageBrowserDecrementIndex;
    }
    function imageBrowserAddImage(ytImg) {
        if (!this.images) {
            var images=new Array();
            this.images = images;
        }
        //ytImg.title1 = "Index: " + this.images.length;
        this.images.push(ytImg);
    }

    function imageBrowserInitDisplay() {
        this.real_images_count = this.images.length;
        
        // Fill with empty images to match page size.
        var empty_slots = this.display_count - (this.images.length % this.display_count);
        if (empty_slots != this.display_count) {
            for (var i=0; i<empty_slots; i++) {
                this.addImage(new ytImage("img/pixel.gif", "", "&nbsp;", "", "&nbsp;", "", true));
            }
        }
        
        for (var i=0; i<this.display_count; i++) {
            this.display_array[i] = this.images[i];
        }

        this.head_index = 0;
        this.tail_index = this.display_array.length-1;
    }
	function imageBrowserIncrementIndex(index) {
        index = index +1;
        if (index > this.images.length-1) {
            index = 0;
        }
		return index;
        
	}
	function imageBrowserDecrementIndex(index) {
        index = index -1;
        if (index < 0) {                     
            index = this.images.length - 1;
        } 
		return index;
	}

    function imageBrowserShiftPageRight() {
        for(var i=0; i<this.display_array.length; i++) {
            this.shiftRight();
        }
    }
    function imageBrowserShiftPageLeft() {
        for(var i=0; i<this.display_array.length; i++) {
            this.shiftLeft();
        }
    }
	function imageBrowserShiftRight() {
        // First shift all elements in display_array, and fill the last one.
        for (var i=0; i< this.display_array.length; i++) {
            this.display_array[i] = this.display_array[i+1];
        }
		this.head_index = this.incrementIndex(this.head_index);
		this.tail_index = this.incrementIndex(this.tail_index);

        this.display_array[this.display_array.length-1] = this.images[this.tail_index];
    }
    
    function imageBrowserShiftLeft() {        
        for (var i=this.display_array.length-1; i > 0; i--) {
            this.display_array[i] = this.display_array[i-1];
        }
		this.head_index = this.decrementIndex(this.head_index);
		this.tail_index = this.decrementIndex(this.tail_index);

		this.display_array[0] = this.images[this.head_index];
    }

    function imageBrowserShowImages() {
        for (var i=0; i<this.display_array.length; i++) {
            tempDivId = "img_" + this.root_div_id + "_" + i;
            block = document.getElementById("img_" + this.root_div_id + "_" + i);
            title1 = document.getElementById("title1_" + this.root_div_id + "_" + i);
            title2 = document.getElementById("title2_" + this.root_div_id + "_" + i);
            title3 = document.getElementById("title3_" + this.root_div_id + "_" + i);
            block.src = this.display_array[i].getImage();
	    title1.innerHTML = this.display_array[i].title1;
            title2.innerHTML = "<span style='color: #333'>" + this.display_array[i].title2  + "</span>";
            if(title3 != null) {
	            title3.innerHTML = "<span style='color: #666'>" + this.display_array[i].title3 + "</span>";
		
            }

            imgUrl = document.getElementById("href_" + this.root_div_id + "_" + i);
            imgUrl.href = this.display_array[i].imageUrl;

            maindiv = document.getElementById("div_" + this.root_div_id + "_" + i);
            maindiv_alternate = document.getElementById("div_" + this.root_div_id + "_" + i + "_alternate");
	    if(this.display_array[i].is_dummy!=false) {
	
		maindiv.style.display="none";
                maindiv_alternate.style.display="";
            } else {
                maindiv.style.display="";
                maindiv_alternate.style.display="none";
		
            }
       }       
	this.printPageNumbers();
       //this.prefetchNextPrevImages();
    }
	


	
    function imageBrowserPrefetchNextPrevImages() {
		// Prefetch the next and previous image
		prev_index = this.decrementIndex(this.head_index);
		this.images[prev_index].loadImage();
		next_index = this.incrementIndex(this.tail_index);
		this.images[next_index].loadImage();
	}
    function imageBrowserPrintPageNumbers() {
        var counter_index = this.head_index;
        for(var i=0; i<this.display_array.length; i++) {
            counter_index = this.incrementIndex(counter_index);
        }
        if (counter_index == 0) {
            counter_index = this.real_images_count;
        }

        counter_div = document.getElementById("counter_" + this.root_div_id);
		if(counter_div) {
			counter_div.innerHTML = "[" + (this.head_index+1) + " - " + counter_index + " of " + this.real_images_count + "]";
		}
    }

    // CLASS ytImage: Store the image and some metadata on it.
    function ytImage(imgSrc, imgUrl, title1, title1Url, title2, title2Url, title3, is_dummy) {
        this.smartImage = new smartImageObject(imgSrc, false);

        this.imageUrl = imgUrl;
        this.is_dummy = is_dummy;
        this.title1_full = title1;
        this.title2_full = title2;

        var max_len = 20;
        if(title1.length > max_len)
            title1 = title1.substring(0, max_len-3) + "..."
            
        if(title2.length > max_len)
            title2 = title2.substring(0, max_len-3) + "..."

        if(title3.length > max_len)
            title3 = title3.substring(0, max_len-3) + "..."
        this.title3=title3
            
        if(title1Url.length > 0)
            this.title1 = "<a href='" + title1Url + "' title='" + this.title1_full + "'>" + title1 + "</a>";
        else 
            this.title1 = title1;

        if(title2Url.length > 0)
            this.title2 = "<a class='dg' href='" + title2Url + "' title='" + this.title2_full + "'>" + title2 + "</a>";
        else
            this.title2 = title2;

        this.getImage = ytGetImageFromSmartImage;
        this.isImageLoaded = ytIsImageLoaded;
		this.loadImage = ytLoadImage
    }

    // CLASS ytImage: custom image
	function customYtImage(imgSrc, imgUrl, title1, title2, title3, is_dummy) {
        this.smartImage = new smartImageObject(imgSrc, false);

        this.imageUrl = imgUrl;
        this.is_dummy = is_dummy;

		this.title1 = title1;
		this.title2 = title2;
		this.title3 = title3;

        this.getImage = ytGetImageFromSmartImage;
        this.isImageLoaded = ytIsImageLoaded;
		this.loadImage = ytLoadImage
    }

    function ytGetImageFromSmartImage() {
        return this.smartImage.getImage();
    }
	function ytIsImageLoaded() {
		return this.smartImage.isImageLoaded();
	}
	function ytLoadImage() {
		if(!this.isImageLoaded()) {
			//this.smartImage.load();
		}
	}

    // CLASS: Smart Image: Pre-loads the image.
	function smartImageObject(imgURI,preload) {
	  this.URI = imgURI;
	  this.imageobj = null;

      // attach object's methods
	  this.load = smartImageObjectLoad; //  force the loading of the image
	  this.getImage = smartImageObjectGetImage;
	  this.isImageLoaded = smartImageIsImageLoaded;
	  if (preload) { 
		//this.load();
	  }
	}
	
	function smartImageIsImageLoaded() {
		if(this.imageobj != null)
			return true;
		else
			return false;
	}
	
	function smartImageObjectLoad() {
	  this.imageobj = new Image(); 
	  this.imageobj.src = this.URI;
	}
	
	function smartImageObjectGetImage() {
	  if (this.imageobj) { 
		return this.imageobj.src;
	  } else { 
		//this.load();
		return this.URI;
	  }
	}
    // Smart Image End.



    // Animation functions for fading in the thumbnail images  
	function opacity(id, opacStart, opacEnd, millisec) {
        //speed for each frame
        var speed = Math.round(millisec / 100);
        var timer = 0;

        //determine the direction for the blending, if start and end are the same nothing happens
        if(opacStart > opacEnd) {
                for(i = opacStart; i >= opacEnd; i--) {
                        setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
                        timer++;
                }
        } else if(opacStart < opacEnd) {
                for(i = opacStart; i <= opacEnd; i++)
                        {
                        setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
                        timer++;
                }
        }
	}

	//change the opacity for different browsers
	function changeOpac(opacity, id) {
        var object = document.getElementById(id).style;
        object.opacity = (opacity / 100);
        object.MozOpacity = (opacity / 100);
        object.KhtmlOpacity = (opacity / 100);
        object.filter = "alpha(opacity=" + opacity + ")";
	}

        function fadeOldImage(id, numColumns) {
          for (i=0;i< numColumns;i++) {
            tempDiv = "img_" + id + "_" + i;
            changeOpac(25, tempDiv);
          }
        }