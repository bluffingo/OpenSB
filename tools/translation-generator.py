from pathlib import Path
import re
import platform

exts = [".twig", ".php"]
paths = [p for p in Path("./").rglob('*') if p.suffix in exts]

strings = []

# add relativetime setting at the very top
strings.append("\\\\RelativeTime\\\\Languages\\\\English")
strings.append("\n")

for path in paths:
    # Ignore composer dependency files
    if platform.system() == "Windows":
        if "vendor\\" in str(path): continue
        if "private/templates\\cache\\" in str(path): continue
    else:
        if "vendor/" in str(path): continue
        if "private/templates/cache/" in str(path): continue

    with open(path, "r", encoding='Latin-1') as f:
        file_matches = re.findall('__\("([^"]+)"', f.read())
        if len(file_matches):
            if len(strings):
                if strings[-1] != "\n":
                    strings.append("\n")

        for match in file_matches:
            # prevent duplicate strings (i.e. 6 "views")
            if match in strings:
                continue
            strings.append(match)

with open("private/lang/template.json", "w") as f:
    f.write("{\n")
    for i in range(0, len(strings)):
        print(strings[i])
        if strings[i] == "\n":
            f.write("\n")
            continue
        f.write('\t"%s": "%s"' % (strings[i], strings[i]))
        if not len(strings) - 1 == i:
            f.write(",")
        f.write('\n')
    f.write("}")
