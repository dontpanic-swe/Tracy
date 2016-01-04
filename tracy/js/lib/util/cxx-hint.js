(function () {
    // get list from mode/clike    
    var cxxKeywordArray = cxxAllWords.split(" ");
    
    function forEach(arr, f) {
        for (var i = 0, e = arr.length; i < e; ++i) f(arr[i]);
    }
  
    function arrayContains(arr, item)
    {
        if (!Array.prototype.indexOf)
        {
            var i = arr.length;
            while (i--)
            {
                if (arr[i] === item)
                    return true;
            }
            return false;
        }
        return arr.indexOf(item) != -1;
    }
    
    function completionData(list,line,token)
    {
        return {
            list: list,
            from: {
                line: line,
                ch: token.start
            },
            to: {
                line: line,
                ch: token.end
            }
        };
    }

    function scriptHint(editor, keywords, getToken)
    {
        // Find the token at the cursor
        var cur = editor.getCursor();
        var token = getToken(editor, cur);
        
        
        var lexstr = "";
        var token_qual_id;
        if ( token.type == "variable" )
        {
            lexstr = token.string;
            token_qual_id = getToken(editor, {line: cur.line, ch: token.start});
        }
        else
            token_qual_id = token;
            
        if ( token.string == ":" &&
             getToken(editor, {line: cur.line, ch: token.start}).type == "variable" )
        {
            // autocomplete ::
            return completionData(["::"],cur.line,token);
        }
        
        while (token_qual_id.string == ":")
        {
            token_qual_id = getToken(editor, {line: cur.line, ch: token_qual_id.start});
            if ( token_qual_id.string != ":" )
                break;
            token_qual_id = getToken(editor, {line: cur.line, ch: token_qual_id.start});
            if ( token_qual_id.type == "variable" )
            {
                lexstr = token_qual_id.string+"::"+lexstr
                token.start = token_qual_id.start;
            }
            else
                break;
            token_qual_id = getToken(editor, {line: cur.line, ch: token_qual_id.start});
        }
    
        var completionList = getCompletions(lexstr); 
        completionList = completionList.sort();
        
        //prevent autocomplete for last word, instead show dropdown with one word
        if(completionList.length == 1)
            completionList.push(" ");

        return completionData(completionList,cur.line,token);
    }
  
    CodeMirror.cxxHint = function(editor)
    {
        return scriptHint(editor, cxxKeywordArray,
                        function (e, cur) {return e.getTokenAt(cur);});
    };
 
    function getCompletions(lexstr)
    {
        var found = [];
        function maybeAdd(str)
        {
            if (str.indexOf(lexstr) == 0 && !arrayContains(found, str))
                found.push(str);
        }
    
        forEach(cxxKeywordArray, maybeAdd);
        forEach(cxxNamespaceHints, maybeAdd);
        
        return found;
    }
})();
