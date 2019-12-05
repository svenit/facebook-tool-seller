function getId(url) 
{
    var string = "";
    var charcompOutput = url.match(/[^](fbid=[0-9]{9})\d+/);
    if (null !== charcompOutput) 
    {
        string = (string = charcompOutput[0].replace("?fbid=", "")).replace("_fbid=", "");
    } 
    else 
    {
        var parts = url.match(/[^\/|\.!=][0-9]{7,}(?!.*[0-9]{7,})\d+/);
        if (null !== parts) 
        {
            string = parts[0];
        }
    }
    return string;
};