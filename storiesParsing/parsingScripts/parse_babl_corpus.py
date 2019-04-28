# Parse children's book dataset (from Facebook bAbl project) into sentences
# Note: this version is very specific to the "cbt_train.txt" dataset.
#       Not all improper sentences are removed, if they were rare enough to
#       remove by hand, in the interest of time efficiency for this project. :)
#
# 4/2019 AriaRay Brown
#

# Open the book text file 
infile = open('cbt_train.txt', 'r') 

# Create a new output text file
# outfile = open('storySentences.txt', 'w')

remove_next_l = False
num_book = 0

# Read each line from file (each line is a sentence) into a string
for l in infile:

    # Remove the next line if the preceding line required it
    if remove_next_l == True:
        l = ""
        # Reset the boolean
        remove_next_l = False

    # Remove \n char from end of line
    if "\n" in l:
            l = l.replace("\n","")
        
    # Remove acronyms from line (from bAbl testing) (i.e. "-LRB-"/"-RRB-")
    acronyms = ["-LRB- ", "-RRB- "]

    for acr in acronyms:
        if acr in l:
            # Replace acronym with empty string to remove it. Reassign val to line
            l = l.replace(acr, "")
    
    # Remove any image caption substrings between "-LCB-" and "-RCB-"
    capt_acronyms = ["-LCB- ", "-RCB- ", "-LSB- ", "-RSB- "]
    acr_length = 6
    
    if "-LCB- " in l:
        
        # Find indices of substring to remove
        indStart = l.find(capt_acronyms[0])
        indEnd = l.find(capt_acronyms[1]) + acr_length

        # Replace substring with empty string
        l = l.replace(l[indStart:indEnd], "")

    # Remove any image caption substrings between "-LSB-" and "-RSB-"
    if "-LSB- " in l:

        # Find indices of substring to remove
        indStart = l.find(capt_acronyms[2])
        indEnd = l.find(capt_acronyms[3]) + acr_length

        # Replace substring with empty string
        l = l.replace(l[indStart:indEnd], "")

    # Split line into a list of words (if line is not empty)
    if l:
        word_list = l.split()
        first = word_list[0]

    # Don't write lines that are chapter titles, or the next line.
    # Note: This may remove a sentence if the next line is not a chapter title.
    if "CHAPTER" in l or "Chapter" in l:
        l = ""
        remove_next_l = True

    # Write each book to a new output file
    if "_BOOK_TITLE_" in l:
        
        bookfilename = "books/book"+str(num_book)+".txt"
        outfile = open(bookfilename, 'w')

        # Increase number of books by 1
        num_book += 1

    # Write non-empty lines to file
    if l:
        # If the first letter is lowercase, combine line with previous sentence
        if first[0].islower() or first == "--":
            outfile.write(" " + l)

        # Write all other full sentences to their own line 
        else:
            outfile.write("\n" + l)
    
    
print('book files have been written in folder "books/".')

# Close files
infile.close()
outfile.close()

